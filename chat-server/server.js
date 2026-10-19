const express = require("express");
const http = require("http");
const { Server } = require("socket.io");
const cors = require("cors");

const port = Number(process.env.PORT || 3000);
const persistenceUrl = process.env.PHP_PERSISTENCE_URL || "http://127.0.0.1/index.php/api/save_message";
const allowedOrigin = process.env.ALLOWED_ORIGIN || "*";

const app = express();
app.use(cors({ origin: allowedOrigin }));

const server = http.createServer(app);
const users = {};

const io = new Server(server, {
	cors: {
		origin: allowedOrigin,
	},
});

io.on("connection", (socket) => {
	console.log("User connected:", socket.id);

	socket.on("join_room", (room) => {
		if (/^chat_\d+$/.test(room)) socket.join(room);
	});

	socket.on("leave_room", room => socket.leave(room));

	socket.on("send_message", async (data, ack = () => {}) => {
		try {
			console.log("📨 Incoming message:", data);

			// 🔥 CAMBIO IMPORTANTE AQUÍ
			const params = new URLSearchParams();
			params.append("chat_id", data.chat_id);
			params.append("user_id", data.user_id);
			params.append("message", data.message);

			const response = await fetch(
				persistenceUrl,
				{
					method: "POST",
					headers: {
						"Content-Type": "application/x-www-form-urlencoded",
					},
					body: params,
				},
			);

			console.log("📡 STATUS:", response.status);

			const result = await response.json();
            if (!response.ok || result.status !== "ok") throw new Error("Persistence failed");
            data.id_messages = result.id_messages;
			console.log("✅ API RESPONSE:", result);

			// 🔥 Emitir mensaje SOLO si se guardó
			io.to("chat_" + Number(data.chat_id)).emit("receive_message", data);
            ack({status: "ok", id_messages: result.id_messages});
		} catch (err) {
			console.error("ERROR saving message:", err);
            ack({status: "error", message: "No se pudo guardar el mensaje."});
		}
	});

	socket.on("disconnect", () => {
		for (const id of Object.keys(users)) if (users[id] === socket.id) delete users[id];
        console.log("User disconnected:", socket.id);
	});

	socket.on("call_user", (data) => {
		const targetSocket = users[data.to];

		if (targetSocket) {
			io.to(targetSocket).emit("incoming_call", {
				from: data.from,
				offer: data.offer,
			});
		}
	});

	socket.on("call_offer", (data) => {
		io.to(data.to).emit("call_offer", data);
	});

	socket.on("call_answer", (data) => {
		const targetSocket = users[data.to];

		if (targetSocket) {
			io.to(targetSocket).emit("call_answer", {
				answer: data.answer,
			});
		}
	});

	socket.on("call_ice_candidate", (data) => {
		const targetSocket = users[data.to];

		if (targetSocket) {
			io.to(targetSocket).emit("call_ice_candidate", {
				candidate: data.candidate,
			});
		}
	});

	socket.on("end_call", (data) => {
		if (users[data.to]) io.to(users[data.to]).emit("end_call");
	});
	socket.on("register_user", (userId) => {
		users[userId] = socket.id;
		console.log("Registered user:", userId, socket.id);
	});
});

server.listen(port, () => {
	console.log(`Servidor WebSocket corriendo en puerto ${port}`);
});
