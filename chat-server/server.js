const express = require("express");
const http = require("http");
const { Server } = require("socket.io");
const cors = require("cors");

const port = Number(process.env.PORT || 3000);
const persistenceUrl =
	process.env.PHP_PERSISTENCE_URL ||
	"http://127.0.0.1/index.php/api/save_message";
const allowedOrigin = process.env.ALLOWED_ORIGIN || "*";

const app = express();
app.use(cors({ origin: allowedOrigin }));

const server = http.createServer(app);
const users = {}; // user ID -> Set of sockets (multiple tabs)
const apiRoot = persistenceUrl.replace(/save_message$/, '');
async function sessionFor(socket) {
    const response = await fetch(apiRoot + 'socket_session', {headers:{Cookie:socket.handshake.headers.cookie || ''}});
    if (!response.ok) throw new Error('Inicia sesión.');
    return response.json();
}
function publishPresence() { io.emit('presence', Object.keys(users).map(Number)); }
function targetFor(id) { return users[id] && [...users[id]].at(-1); }

const io = new Server(server, {
	cors: {
		origin: allowedOrigin,
	},
});

io.use(async (socket, next) => {
    try { socket.data.user = await sessionFor(socket); next(); }
    catch (error) { next(new Error('Inicia sesión para conectar el chat.')); }
});
io.on("connection", (socket) => {
	console.log("User connected:", socket.id);

	socket.on("join_room", async (room) => {
        if (!/^chat_\d+$/.test(room)) return;
        try {
            socket.data.user = await sessionFor(socket);
            if (socket.data.user.chats.includes(Number(room.slice(5)))) socket.join(room);
        } catch (_) { socket.disconnect(true); }
    });

	socket.on("leave_room", (room) => socket.leave(room));

	socket.on("send_message", async (data, ack = () => {}) => {
		try {


			const params = new URLSearchParams();
			params.append("chat_id", data.chat_id);
			params.append("user_id", socket.data.user.id);
            params.append("encrypted", data.encrypted ? "1" : "0");
			params.append("message", data.message);

			const response = await fetch(persistenceUrl, {
				method: "POST",
				headers: {
					"Content-Type": "application/x-www-form-urlencoded",
                    Cookie: socket.handshake.headers.cookie || "",
				},
				body: params,
			});

			console.log("📡 STATUS:", response.status);

			const result = await response.json();
			if (!response.ok || result.status !== "ok")
				throw new Error("Persistence failed");
			data = {chat_id:Number(data.chat_id), id_messages:result.id_messages, message:result.message,
                user_id:result.user_id, user_name:result.user_name, is_encrypted:result.is_encrypted};


			// Emitir mensaje SOLO si se guardó
			// One union emission reaches active and inactive conversations without duplicates.
            io.to(result.participants.map(id => 'user_' + id)).emit("receive_message", data);
			ack({ status: "ok", id_messages: result.id_messages });
		} catch (err) {
			console.error("ERROR saving message:", err);
			ack({ status: "error", message: "No se pudo guardar el mensaje." });
		}
	});

	socket.on("disconnect", () => {
		for (const id of Object.keys(users))
			if (users[id].delete(socket.id) && users[id].size === 0) delete users[id];
        publishPresence();
		console.log("User disconnected:", socket.id);
	});

	socket.on("call_user", (data) => {
		const targetSocket = targetFor(data.to);

		if (targetSocket) {
			io.to(targetSocket).emit("incoming_call", {
				from: socket.data.user.id,
				offer: data.offer,
			});
		}
	});



	socket.on("call_answer", (data) => {
		const targetSocket = targetFor(data.to);

		if (targetSocket) {
			io.to(targetSocket).emit("call_answer", {
				answer: data.answer,
			});
		}
	});

	socket.on("call_ice_candidate", (data) => {
		const targetSocket = targetFor(data.to);

		if (targetSocket) {
			io.to(targetSocket).emit("call_ice_candidate", {
				candidate: data.candidate,
			});
		}
	});

    socket.on('chat_created', async (chatId) => {
        try {
            const session = await sessionFor(socket);
            if (!session.chats.includes(Number(chatId))) return;
            // Each client refreshes its own authorized list, never another user's data.
            io.emit('chats_changed');
        } catch (_) {}
    });

	socket.on("end_call", (data) => {
		if (targetFor(data.to)) io.to(targetFor(data.to)).emit("end_call");
	});
	socket.on("register_user", (userId) => {
        userId = socket.data.user.id;
        if (!users[userId]) users[userId] = new Set();
        users[userId].add(socket.id);
        socket.join('user_' + userId);
        publishPresence();
		console.log("Registered user:", userId, socket.id);
	});
});

server.listen(port, () => {
	console.log(`Servidor WebSocket corriendo en puerto ${port}`);
});
