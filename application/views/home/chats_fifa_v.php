<script src="https://cdn.socket.io/4.7.5/socket.io.min.js"></script>
<script>
    const BASE_URL = "<?= base_url(); ?>";
    const userId = <?= $this->session->userdata('id'); ?>;
    const userName = "<?= $this->session->userdata('first_name'); ?>";
    const userLastName = "<?= $this->session->userdata('last_name'); ?>";
    const userImage = "<?= base_url($this->session->userdata('profile_picture')); ?>";
</script>
<script>
const socket = io("http://192.168.100.5:3000");

let currentChatId = 2;
let currentRoom = "chat_" + currentChatId;


// SEND MESSAGE
function sendMessage() {

    const input = document.getElementById("message");
    const message = input.value;

    if (message.trim() === "") return;

    socket.emit("send_message", {
        message: message,
        user_name: userName,
        user_id: userId,
        user_image: userImage, // 🔥 IMPORTANTE
        chat_id: currentChatId,
        room: currentRoom
    });


    input.value = "";
}

// RECEIVE MESSAGE
socket.on("receive_message", (data) => {

    const chat = document.getElementById("chat");

    const isMine = data.user_id === userId;

    const messageHTML = `
        <div class="flex ${isMine ? "justify-end" : "justify-start"} items-end gap-2 mb-3">

            ${!isMine ? `
                <img src="${data.user_image}" 
                    class="w-8 h-8 rounded-full object-cover border border-green-500">
            ` : ""}

            <div class="
                px-4 py-2 rounded-2xl max-w-xs shadow
                ${isMine 
                    ? "bg-green-500 text-white" 
                    : "bg-slate-700 text-gray-500 border border-green-700"}
            ">
                <p class="text-xs opacity-70 mb-1">${data.user_name}</p>
                <p>${data.message}</p>
            </div>

            ${isMine ? `
                <img src="${userImage}" 
                    class="w-8 h-8 rounded-full object-cover border border-yellow-400">
            ` : ""}

        </div>
    `;


    chat.innerHTML += messageHTML;
    chat.scrollTop = chat.scrollHeight;
});

// LOAD HISTORY
async function loadMessages(chat_id) {

    const response = await fetch(`${BASE_URL}index.php/api/messages/${chat_id}`);
    const messages = await response.json();

    const chat = document.getElementById("chat");
    chat.innerHTML = "";

    messages.forEach(msg => {

        const isMine = msg.id_employee == userId;

        const messageHTML = `
            <div class="flex ${isMine ? "justify-end" : "justify-start"} items-end gap-2 mb-3">

                ${!isMine ? `
                    <img src="${BASE_URL + msg.profile_picture}" 
                        class="w-8 h-8 rounded-full object-cover border border-green-500">
                ` : ""}

                <div class="
                    px-4 py-2 rounded-2xl max-w-xs shadow
                    ${isMine 
                        ? "bg-green-500 text-white" 
                        : "bg-slate-700 text-gray-500 border border-green-700"}
                ">
                    <p class="text-xs opacity-70 mb-1">
                        ${msg.first_name} ${msg.last_name}
                    </p>
                    <p>${msg.message}</p>
                </div>

                ${isMine ? `
                    <img src="${userImage}" 
                        class="w-8 h-8 rounded-full object-cover border border-yellow-400">
                ` : ""}

            </div>
        `;

        chat.innerHTML += messageHTML;
    });



    chat.scrollTop = chat.scrollHeight;
}
async function loadChats() {

        const response = await fetch(`${BASE_URL}index.php/api/user_chats/${userId}`);
        const chats = await response.json();

        const container = document.getElementById("chatList");
        container.innerHTML = "";

        chats.forEach(chat => {

            const chatHTML = `
                    <div onclick="openChat(
                        ${chat.id_chats}, 
                        '${chat.type === "grupo" 
                            ? chat.name 
                            : chat.first_name + " " + chat.last_name}',
                        '${BASE_URL + chat.profile_picture}',
                        ${chat.other_user_id}
                    )"
                  "
                    class="bg-slate-800 hover:bg-green-800 p-4 rounded-2xl cursor-pointer transition shadow-md border border-green-700">

                    <div class="flex items-center gap-3">

                        <img src="${BASE_URL + chat.profile_picture}" 
                            class="w-10 h-10 rounded-full object-cover">

                        <div class="flex-1">
                            <div class="flex justify-between items-center">
                                <h3 class="font-semibold text-yellow-400">
                                    ${chat.type === "grupo" 
                                        ? chat.name 
                                        : chat.first_name + " "}
                                </h3>

                                <span class="text-xs text-gray-400">
                                    ${chat.last_time ?? ""}
                                </span>
                            </div>

                            <p class="text-sm text-gray-300 truncate">
                                ${chat.last_message ?? "Sin mensajes"}
                            </p>
                        </div>

                    </div>
                </div>
            `;


            container.innerHTML += chatHTML;
        });
    }

function openChat(chatId, chatName, chatImage, peerId) {

    currentChatId = chatId;
    currentRoom = "chat_" + chatId;
    currentPeerId = peerId;

    socket.emit("join_room", currentRoom);

    document.getElementById("chatTitle").innerText = chatName;
    document.getElementById("chatAvatar").src = chatImage;

    loadMessages(chatId);
}




// INIT
window.onload = () => {
    socket.emit("join_room", currentRoom);
    loadMessages(currentChatId);
    loadChats();
};

</script>


<div class="min-h-screen flex bg-gradient-to-br from-slate-900 via-green-900 to-slate-800 p-6 text-white">
    
    <!-- Chat Container -->
    <div class="w-full max-w-7xl mx-auto bg-slate-900/80 backdrop-blur-lg rounded-3xl shadow-2xl flex overflow-hidden border border-green-700 relative">
        
        <!-- Sidebar -->
        <div class="w-80 bg-slate-950 p-6 flex flex-col">
            
            <h2 class="text-2xl font-bold text-yellow-400 mb-6">
                Chats ⚽
            </h2>
            <button style="background-color: green;" onclick="openCreateGroupModal()">
                Crear Grupo
            </button>
            <br>
            <!-- Search -->
            <div class="mb-6">
                <input 
                    type="text" 
                    placeholder="Buscar equipos o jugadores..."
                    class="w-full px-4 py-2 rounded-full bg-slate-800 text-black placeholder-gray-400 focus:outline-none border border-green-700"
                >
            </div>

            <!-- Conversations -->
            <div id="chatList" class="flex flex-col gap-4 overflow-y-auto">
            </div>
        
        </div>

        <!-- Chat Area -->
        <div class="flex-1 flex flex-col">

            <!-- Chat Header -->
            <div class="p-6 border-b border-green-700 flex justify-between items-center bg-slate-900">
                <div class="flex items-center gap-3">
                    <img id="chatAvatar" class="w-10 h-10 rounded-full object-cover border border-yellow-400">
                    
                    <div>
                        <h3 id="chatTitle" class="text-xl font-bold text-yellow-400"></h3>
                        <p id="chatStatus" class="text-sm text-gray-400"></p>
                    </div>
                </div>

                <div class="flex gap-4 text-green-400 text-xl">
                    <button onclick="startCall()" class="hover:text-yellow-400 transition">
                        📞
                    </button>
                    <button class="hover:text-yellow-400 transition">🎥</button>
                    <button class="hover:text-yellow-400 transition">⚙️</button>
                </div>
            </div>
            <div class="fixed bottom-4 right-4 flex gap-4">
                <video id="localVideo" autoplay muted class="w-40 rounded-lg border"></video>
                <video id="remoteVideo" autoplay class="w-40 rounded-lg border"></video>
            </div>


            <!-- Messages -->
            <div class="flex-1 p-6 overflow-y-auto flex flex-col gap-4 bg-slate-900">
            <div id="chat"></div>
            
            </div>
            

            <!-- Message Input -->

            <div class="p-6 border-t border-green-700 bg-slate-950">
                <div class="flex items-center gap-4 bg-slate-800 px-4 py-3 rounded-full border border-green-700 shadow-inner">

                    <button class="text-green-400 text-xl">➕</button>

                    <input 
                        type="text"
                        id="message" 
                        placeholder="Escribe tu jugada..."
                        class="flex-1 bg-transparent focus:outline-none text-black placeholder-gray-400"
                    >

                    <button onclick="sendMessage()" class="bg-yellow-400 hover:bg-yellow-500 text-black px-6 py-2 rounded-full font-semibold shadow-md transition">
                        Enviar ⚽
                    </button>

                </div>
            </div>

        </div>

    </div>

</div>

<!-- CREATE GROUP MODAL -->
<div 
    id="groupModal"
    class="hidden fixed inset-0 bg-black/70 flex items-center justify-center z-[9999]"
>

    <div class="bg-slate-900 border border-green-700 rounded-3xl p-6 w-[400px] shadow-2xl">

        <div class="flex justify-between items-center mb-4">
            <h2 class="text-2xl font-bold text-yellow-400">
                Crear Grupo ⚽
            </h2>

            <button 
                onclick="closeCreateGroupModal()"
                class="text-red-400 text-xl"
            >
                ✖
            </button>
        </div>

        <!-- Group Name -->
        <input 
            type="text"
            id="groupName"
            placeholder="Nombre del grupo"
            class="w-full mb-4 px-4 py-3 rounded-xl bg-slate-800 text-white border border-green-700 focus:outline-none"
        >
        <!-- GROUP IMAGE -->
        <div class="mb-4">

            <label class="block mb-2 text-sm text-gray-300">
                Foto del grupo
            </label>

            <input 
                type="file"
                id="groupImage"
                accept="image/*"
                class="w-full bg-slate-800 border border-green-700 rounded-xl p-2"
            >

        </div>


        <!-- Users -->
        <div 
            id="usersList"
            class="max-h-64 overflow-y-auto flex flex-col gap-2 mb-4"
        >
        </div>

        <!-- Create -->
        <button
            onclick="createGroup()"
            class="w-full bg-green-600 hover:bg-green-700 py-3 rounded-xl font-bold transition"
        >
            Crear Grupo
        </button>

    </div>

</div>

<script>
    
    let currentPeerId = null;
    let localStream;
    let peerConnection;
    socket.on("connect", () => {
        socket.emit("register_user", userId);
    });


    const configuration = {
        iceServers: [
            { urls: "stun:stun.l.google.com:19302" }
        ]
};


    function openCreateGroupModal() {

        document
            .getElementById("groupModal")
            .classList.remove("hidden");

        loadUsers();
    }

    function closeCreateGroupModal() {

        document
            .getElementById("groupModal")
            .classList.add("hidden");
    }

    async function loadUsers() {

        const response = await fetch(
            `${BASE_URL}index.php/api/users`
        );

        const users = await response.json();

        const container = document.getElementById("usersList");

        container.innerHTML = "";

        users.forEach(user => {

            // evitar mostrarme a mí
            if(user.id == userId) return;

            container.innerHTML += `

                <label class="
                    flex items-center gap-3
                    bg-slate-800
                    p-3
                    rounded-xl
                    cursor-pointer
                    hover:bg-green-800
                    transition
                ">

                    <input 
                        type="checkbox"
                        value="${user.id}"
                    >

                    <img 
                        src="${BASE_URL + user.profile_picture}"
                        class="w-10 h-10 rounded-full object-cover"
                    >

                    <span>
                        ${user.first_name} ${user.last_name}
                    </span>

                </label>
            `;
        });
    }

    async function createGroup() {

        const groupName = document
            .getElementById("groupName")
            .value;

        const groupImage = document
            .getElementById("groupImage")
            .files[0];

        const selectedUsers = [
            ...document.querySelectorAll(
                '#usersList input:checked'
            )
        ].map(cb => cb.value);

        // agregarme automáticamente
        selectedUsers.push(userId);

        if(groupName.trim() === "") {
            alert("Pon nombre al grupo");
            return;
        }

        if(selectedUsers.length < 3) {
            alert("Selecciona mínimo 2 usuarios");
            return;
        }

        const formData = new FormData();

        formData.append("type", "grupo");
        formData.append("name", groupName);

        if(groupImage) {
            formData.append("group_image", groupImage);
        }

        selectedUsers.forEach(user => {
            formData.append("participants[]", user);
        });

        const response = await fetch(
            `${BASE_URL}index.php/api/create_chat`,
            {
                method: "POST",
                body: formData
            }
        );

        const result = await response.json();

        console.log(result);

        closeCreateGroupModal();

        loadChats();
    }

    async function getMediaStream() {
        const mediaDevices = navigator.mediaDevices;

        if (!mediaDevices || !mediaDevices.getUserMedia) {
            alert("Tu navegador no soporta cámara/micrófono en este contexto");
            return null;
        }

        try {
            return await mediaDevices.getUserMedia({
                video: true,
                audio: true
            });
        } catch (err) {
            console.error("Error accessing media devices:", err);
            alert("No se pudo acceder a cámara/micrófono");
            return null;
        }
    }


    async function startCall() {

        localStream = await getMediaStream();
        if (!localStream) return;

        document.getElementById("localVideo").srcObject = localStream;

        peerConnection = new RTCPeerConnection(configuration);

        localStream.getTracks().forEach(track => {
            peerConnection.addTrack(track, localStream);
        });

        peerConnection.ontrack = (event) => {
            document.getElementById("remoteVideo").srcObject = event.streams[0];
        };

        peerConnection.onicecandidate = (event) => {
            if (event.candidate) {
                socket.emit("call_ice_candidate", {
                    to: currentPeerId,
                    candidate: event.candidate
                });
            }
        };

        const offer = await peerConnection.createOffer();
        await peerConnection.setLocalDescription(offer);

        socket.emit("call_user", {
            to: currentPeerId,
            from: userId,
            room: currentRoom,
            offer: offer
        });
    }


    socket.on("incoming_call", async (data) => {

        const accept = confirm("📞 Incoming call. Accept?");

        if (!accept) return;

        localStream = await navigator.mediaDevices.getUserMedia({
            video: true,
            audio: true
        });

        document.getElementById("localVideo").srcObject = localStream;

        peerConnection = new RTCPeerConnection(configuration);

        localStream.getTracks().forEach(track => {
            peerConnection.addTrack(track, localStream);
        });

        peerConnection.ontrack = (event) => {
            document.getElementById("remoteVideo").srcObject = event.streams[0];
        };

        peerConnection.onicecandidate = (event) => {
            if (event.candidate) {
                socket.emit("call_ice_candidate", {
                    to: data.from,
                    candidate: event.candidate
                });
            }
        };

        await peerConnection.setRemoteDescription(
            new RTCSessionDescription(data.offer)
        );

        const answer = await peerConnection.createAnswer();
        await peerConnection.setLocalDescription(answer);

        socket.emit("call_answer", {
            to: data.from,
            answer: answer
        });
    });

    socket.on("call_answer", async (data) => {
        await peerConnection.setRemoteDescription(
            new RTCSessionDescription(data.answer)
        );
    });
    
    socket.on("call_ice_candidate", async (data) => {
        if (data.candidate) {
            await peerConnection.addIceCandidate(
                new RTCIceCandidate(data.candidate)
            );
        }
    });

    function endCall() {

        if (peerConnection) {
            peerConnection.close();
            peerConnection = null;
        }

        if (localStream) {
            localStream.getTracks().forEach(track => track.stop());
        }

        socket.emit("end_call", {
            to: currentChatId
        });
    }



</script>
