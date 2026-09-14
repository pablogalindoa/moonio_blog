<script src="https://cdn.socket.io/4.7.5/socket.io.min.js"></script>

<script>
    const socket = io(<?= json_encode($this->config->item('socket_io_url') ?? 'http://localhost:3000'); ?> || window.location.origin);

    // Enviar mensaje
    function enviarMensaje() {
        const mensaje = document.getElementById("mensaje").value;

        socket.emit("send_message", {
            mensaje: mensaje,
            usuario: "Pablo"
        });
    }

    // Recibir mensaje
    socket.on("receive_message", (data) => {
        const chat = document.getElementById("chat");

        chat.innerHTML += `
            <div>
                <strong>${data.usuario}:</strong> ${data.mensaje}
            </div>
        `;
    });
</script>


<div class="min-h-screen flex bg-gradient-to-br from-purple-100 via-pink-100 to-purple-200 p-6">

    <!-- Chat Container -->
    <div class="w-full max-w-7xl mx-auto bg-white/60 backdrop-blur-lg rounded-3xl shadow-2xl flex overflow-hidden">

        <!-- Sidebar -->
        <div class="w-80 bg-white/70 backdrop-blur-md p-6 flex flex-col">

            <h2 class="text-2xl font-bold text-purple-700 mb-6">
                Chats 💬
            </h2>

            <!-- Search -->
            <div class="mb-6">
                <input 
                    type="text" 
                    placeholder="Busca conversaciones..."
                    class="w-full px-4 py-2 rounded-full bg-purple-100 text-purple-600 placeholder-purple-400 focus:outline-none"
                >
            </div>

            <!-- Conversations -->
            <div class="flex flex-col gap-4 overflow-y-auto">

                <!-- Individual Chat -->
                <div class="bg-purple-100 hover:bg-purple-200 p-4 rounded-2xl cursor-pointer transition shadow-sm">
                    <div class="flex justify-between items-center">
                        <h3 class="font-semibold text-purple-700">Pablo 🌿</h3>
                        <span class="text-xs text-purple-500">2m ago</span>
                    </div>
                    <p class="text-sm text-purple-500 mt-1 truncate">
                        Hola! Nos veremos al rato?
                    </p>
                </div>

                <!-- Group Chat -->
                <div class="bg-purple-100 hover:bg-purple-200 p-4 rounded-2xl cursor-pointer transition shadow-sm">
                    <div class="flex justify-between items-center">
                        <h3 class="font-semibold text-purple-700">Equipo de Diseño ✨</h3>
                        <span class="text-xs text-purple-500">10m ago</span>
                    </div>
                    <p class="text-sm text-purple-500 mt-1 truncate">
                        Ivanna: Ya subí los nuevos modelos 3D 🥳
                    </p>
                </div>

                <!-- Another Chat -->
                <div class="bg-purple-100 hover:bg-purple-200 p-4 rounded-2xl cursor-pointer transition shadow-sm">
                    <div class="flex justify-between items-center">
                        <h3 class="font-semibold text-purple-700">Fran 🎧</h3>
                        <span class="text-xs text-purple-500">1h ago</span>
                    </div>
                    <p class="text-sm text-purple-500 mt-1 truncate">
                        Subiré los archivos pronto, se me hizo tarde!
                    </p>
                </div>

            </div>

        </div>

        <!-- Chat Area -->
        <div class="flex-1 flex flex-col">

            <!-- Chat Header -->
            <div class="p-6 border-b border-purple-200 flex justify-between items-center">
                <div>
                    <h3 class="text-xl font-bold text-purple-700">
                        Equipo de Diseño ✨
                    </h3>
                    <p class="text-sm text-purple-500">
                        5 miembros en línea
                    </p>
                </div>

                <div class="flex gap-4 text-purple-600 text-xl">
                    <button class="hover:text-purple-800 transition">📞</button>
                    <button class="hover:text-purple-800 transition">🎥</button>
                    <button class="hover:text-purple-800 transition">⚙️</button>
                </div>
            </div>

            <!-- Messages -->
            <div class="flex-1 p-6 overflow-y-auto flex flex-col gap-4">

                <!-- Incoming Message -->
                <div class="flex items-start gap-3">
                    <div class="bg-purple-200 text-purple-700 px-4 py-3 rounded-2xl max-w-md shadow-sm">
                        Ya subí los nuevos modelos 3D 🥳
                    </div>
                </div>

                <!-- Outgoing Message -->
                <div class="flex justify-end">
                    <div class="bg-purple-400 text-white px-4 py-3 rounded-2xl max-w-md shadow-md">
                        Se ven geniales! Bien hecho ✨
                    </div>
                </div>

                <!-- Another Incoming -->
                <div class="flex items-start gap-3">
                    <div class="bg-purple-200 text-purple-700 px-4 py-3 rounded-2xl max-w-md shadow-sm">
                        Podríamos hacer una revisión general más tarde?
                    </div>
                </div>

            </div>

            <!-- Message Input -->
            <div id="chat"></div>
            <div class="p-6 border-t border-purple-200">
                <div class="flex items-center gap-4 bg-purple-100 px-4 py-3 rounded-full shadow-inner">

                    <button class="text-purple-600 text-xl">➕</button>

                    <input 
                        type="text" 
                        id="mensaje"
                        placeholder="Escribe un mensajito..."
                        class="flex-1 bg-transparent focus:outline-none text-purple-700 placeholder-purple-400"
                    >
                    <button onclick="enviarMensaje()" class="bg-purple-400 hover:bg-purple-500 text-white px-6 py-2 rounded-full shadow-md transition">
                        Enviar 🌙
                    </button>

                </div>
            </div>

        </div>

    </div>

</div>
