const express = require("express");
const http = require("http");
const { Server } = require("socket.io");
const cors = require("cors");

const app = express();
app.use(cors());

const server = http.createServer(app);
const users = {};

const io = new Server(server, {
	cors: {
		origin: "*",
	},
});

io.on("connection", (socket) => {
	console.log("User connected:", socket.id);

	socket.on("join_room", (room) => {
		socket.join(room);
	});

	socket.on("send_message", async (data) => {
		try {
			console.log("📨 Incoming message:", data);

			// 🔥 CAMBIO IMPORTANTE AQUÍ
			const params = new URLSearchParams();
			params.append("chat_id", data.chat_id);
			params.append("user_id", data.user_id);
			params.append("message", data.message);

			const response = await fetch(
				"http://127.0.0.1/index.php/api/save_message",
				{
					method: "POST",
					headers: {
						"Content-Type": "application/x-www-form-urlencoded",
					},
					body: params,
				},
			);

			console.log("📡 STATUS:", response.status);

			const result = await response.text();
			console.log("✅ API RESPONSE:", result);

			// 🔥 Emitir mensaje SOLO si se guardó
			io.to(data.room).emit("receive_message", data);
		} catch (err) {
			console.error("❌ ERROR saving message:", err);
		}
	});

	socket.on("disconnect", () => {
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
		io.to(data.to).emit("end_call");
	});
	socket.on("register_user", (userId) => {
		users[userId] = socket.id;
		console.log("Registered user:", userId, socket.id);
	});
});

server.listen(3000, () => {
	console.log("Servidor WebSocket corriendo en puerto 3000");
});
