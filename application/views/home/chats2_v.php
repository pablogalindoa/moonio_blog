<script src="/socket.io/socket.io.js"></script>
<script>
const BASE_URL = <?= json_encode(base_url()); ?>;
const userId = <?= json_encode((int)$this->session->userdata('id')); ?>;
const userName = <?= json_encode($this->session->userdata('first_name')); ?>;
const userImage = <?= json_encode(base_url($this->session->userdata('profile_picture'))); ?>;
const socket = io(window.location.origin);
let currentChatId = null, currentRoom = null, pendingSend = false;
const visibleMessages = new Set();
function notice(text) { document.getElementById('chatStatus').textContent = text; }
function renderMessage(msg) {
    const id = String(msg.id_messages);
    if (visibleMessages.has(id)) return;
    visibleMessages.add(id);
    const box = document.createElement('div');
    box.dataset.messageId = id;
    box.className = 'chat-message ' + (Number(msg.id_employee ?? msg.user_id) === userId ? 'mine' : '');
    const name = document.createElement('small');
    name.textContent = msg.first_name ?? msg.user_name ?? '';
    box.append(name, document.createElement('br'));
    const body = String(msg.message);
    for (const part of body.split(/(https?:\/\/[^\s]+|\/blog\/index\.php\/ftp\/download\/\d+)/g)) {
        if (/^(https?:\/\/|\/blog\/index\.php\/ftp\/download\/)/.test(part)) {
            const link = document.createElement('a');
            link.href = part; link.textContent = part; link.target = '_blank'; link.rel = 'noopener noreferrer'; box.append(link);
        } else box.append(document.createTextNode(part));
    }
    const chat = document.getElementById('chat'); chat.append(box);
    [...chat.children].sort((a,b)=>Number(a.dataset.messageId)-Number(b.dataset.messageId)).forEach(node=>chat.append(node));
    box.scrollIntoView({block:'nearest'});
}
async function sendText(message, chatId = currentChatId) {
    if (!chatId) throw new Error('Selecciona una conversación.');
    if (!socket.connected) throw new Error('Sin conexión. Espera a reconectar.');
    return new Promise((resolve, reject) => socket.timeout(15000).emit('send_message', {
        message, user_name:userName, user_id:userId, chat_id:chatId, room:'chat_' + chatId
    }, (err, result) => {
        if (err) return reject(new Error('Sin confirmación. Revisa el historial antes de reenviar.'));
        if (result.status !== 'ok') return reject(new Error(result.message));
        resolve(result);
    }));
}
async function sendMessage() {
    const input = document.getElementById('message');
    if (pendingSend || !input.value.trim()) return;
    pendingSend = true;
    try { await sendText(input.value.trim()); input.value = ''; notice('Guardado'); }
    catch(e) { notice(e.message); }
    finally { pendingSend = false; }
}
socket.on('receive_message', data => { if (Number(data.chat_id) === currentChatId) renderMessage(data); loadChats().catch(e=>notice(e.message)); });
async function loadMessages(chatId) {
    const response = await fetch(`${BASE_URL}index.php/api/messages/${chatId}`);
    if (!response.ok) throw new Error('No se pudo cargar el historial.');
    const messages = await response.json();
    if (currentChatId !== chatId) return;
    messages.forEach(renderMessage);
    // History and live delivery can overlap: merge by database ID, then order.
    const chat = document.getElementById('chat');
}
async function loadChats() {
    const response = await fetch(`${BASE_URL}index.php/api/user_chats/${userId}`);
    const chats = await response.json();
    const list = document.getElementById('chatList'); list.replaceChildren();
    chats.forEach(chat => {
        const button = document.createElement('button'); button.className = 'chat-action';
        const name = chat.type === 'grupo' ? chat.name : chat.first_name + ' ' + chat.last_name;
        button.textContent = name;
        button.dataset.chatId = chat.id_chats;
        button.setAttribute('aria-pressed', String(Number(chat.id_chats) === currentChatId));
        button.onclick = () => openChat(Number(chat.id_chats), name, BASE_URL + (chat.profile_picture || ''), chat.other_user_id);
        list.append(button);
    });
}
function openChat(chatId, name, image, peerId) {
    if (currentRoom) socket.emit('leave_room', currentRoom);
    currentChatId = chatId; currentRoom = 'chat_' + chatId; currentPeerId = peerId;
    document.querySelectorAll('#chatList button').forEach(button => button.setAttribute('aria-pressed', String(Number(button.dataset.chatId) === chatId)));
    visibleMessages.clear(); document.getElementById('chat').replaceChildren();
    document.getElementById('chatTitle').textContent = name;
    document.getElementById('chatAvatar').src = image;
    socket.emit('join_room', currentRoom);
    loadMessages(chatId).catch(e=>notice(e.message));
}
function shareLocation() {
    if (!currentChatId) return notice('Selecciona una conversación.');
    if (!window.isSecureContext) return notice('Ubicación requiere HTTPS de confianza en este dispositivo.');
    if (!navigator.geolocation) return notice('Este navegador no ofrece ubicación.');
    const target = currentChatId;
    notice('Obteniendo ubicación…');
    navigator.geolocation.getCurrentPosition(async position => {
        try { await sendText(`📍 Ubicación: https://www.google.com/maps?q=${position.coords.latitude},${position.coords.longitude}`, target); notice('Ubicación guardada'); }
        catch(e) { notice(e.message); }
    }, error => notice(error.code === 1 ? 'Permiso de ubicación denegado. Habilítalo en los permisos del sitio.' : 'No se pudo obtener la ubicación. Revisa GPS/conexión e intenta de nuevo.'), {timeout:15000, maximumAge:0});
}
function pickFile() {
    if (!currentChatId) return notice('Selecciona una conversación.');
    document.getElementById('chatFile').click();
}
async function shareFile(input) {
    const file = input.files[0], target = currentChatId; if (!file) return;
    if (file.size > 20 * 1024 * 1024) { input.value=''; return notice('Máximo 20 MB.'); }
    const form = new FormData(); form.append('userfile', file); form.append('chat_upload', '1');
    notice('Subiendo archivo…');
    try {
        const response = await fetch(`${BASE_URL}index.php/ftp/upload`, {method:'POST', body:form});
        const result = await response.json();
        if (!response.ok || result.status !== 'ok') throw new Error(result.message || 'Error al subir archivo.');
        await sendText('📎 Archivo: ' + file.name + ' ' + new URL(result.url, location.origin).pathname, target);
        notice('Archivo compartido y mensaje guardado');
    } catch(e) { notice(e.message); }
    finally { input.value=''; }
}
window.addEventListener('DOMContentLoaded', () => {
    loadChats().catch(e=>notice(e.message));
    const preview = document.querySelector('.moon-call-preview');
    const videos = [...preview.querySelectorAll('video')];
    function updatePreviewVisibility() {
        videos.forEach(video => {
            const active = Boolean(video.srcObject && video.readyState >= 1 &&
                video.srcObject.getVideoTracks().some(track => track.readyState === 'live'));
            video.hidden = !active;
            video.style.display = active ? 'block' : 'none';
        });
        const active = videos.some(video => !video.hidden);
        preview.hidden = !active;
        preview.style.display = active ? 'flex' : 'none';
    }
    videos.forEach(video => {
        video.addEventListener('loadedmetadata', () => {
            video.srcObject?.getVideoTracks().forEach(track => track.addEventListener('ended', updatePreviewVisibility, {once:true}));
            updatePreviewVisibility();
        });
        video.addEventListener('emptied', updatePreviewVisibility);
    });
    updatePreviewVisibility();
    document.getElementById('message').addEventListener('keydown', e=>{if(e.key==='Enter') sendMessage();});
});
</script>



<div class="moon-chat-page min-h-screen flex bg-gradient-to-br from-slate-900 via-purple-950 to-slate-800 p-6 text-white">

    <!-- Chat Container -->
    <div class="w-full max-w-7xl mx-auto bg-slate-900/80 backdrop-blur-lg rounded-3xl shadow-2xl flex chat-shell overflow-hidden border border-purple-700 relative">

        <!-- Sidebar -->
        <div class="chat-sidebar w-80 bg-slate-950 p-6 flex flex-col">

            <h2 class="text-2xl font-bold text-purple-300 mb-6">
                Chats
            </h2>
            <button class="chat-action" onclick="openCreateGroupModal()">
                Nueva conversación
            </button>
            <br>
            <!-- Search -->
            <div class="mb-6">
                <input
                    type="text"
                    placeholder="Buscar grupos o usuarios..."
                    class="w-full px-4 py-2 rounded-full bg-slate-800 text-white placeholder-gray-400 focus:outline-none border border-purple-700"
                >
            </div>

            <!-- Conversations -->
            <div id="chatList" class="flex flex-col gap-4 overflow-y-auto">
            </div>

        </div>

        <!-- Chat Area -->
        <div class="moon-chat-main flex-1 flex flex-col">

            <!-- Chat Header -->
            <div class="moon-chat-header p-6 border-b border-purple-700 flex justify-between items-center bg-slate-900">
                <div class="flex items-center gap-3">
                    <img id="chatAvatar" class="w-10 h-10 rounded-full object-cover border border-yellow-400">

                    <div>
                        <h3 id="chatTitle" class="text-xl font-bold text-purple-300"></h3>
                        <p id="chatStatus" class="text-sm text-gray-400"></p>
                    </div>
                </div>

                <div class="moon-chat-actions flex flex-wrap gap-2">
                    <button class="chat-action" onclick="startCall().catch(e=>{notice(e.message); endCall();})"><span class="moon-icon" aria-hidden="true">📹</span> Videollamada</button>
                    <button class="chat-action" onclick="shareLocation()"><span class="moon-icon" aria-hidden="true">📍</span> Ubicación</button>
                    <button class="chat-action" onclick="pickFile()"><span class="moon-icon" aria-hidden="true">📎</span> Archivo</button>
                    <button class="chat-action chat-hangup" onclick="endCall()">Colgar</button>
                    <input id="chatFile" type="file" hidden onchange="shareFile(this)">
                </div>
            </div>
            <div class="moon-call-preview fixed bottom-4 right-4 gap-4" hidden style="display:none">
                <video id="localVideo" hidden style="display:none" autoplay playsinline muted class="w-40 rounded-lg border"></video>
                <video id="remoteVideo" hidden style="display:none" autoplay playsinline class="w-40 rounded-lg border"></video>
            </div>


            <!-- Messages -->
            <div class="flex-1 p-6 overflow-y-auto flex flex-col gap-4 bg-slate-900">
            <div id="chat"></div>

            </div>


            <!-- Message Input -->

            <div class="moon-chat-composer p-6 border-t border-purple-700 bg-slate-950">
                <div class="flex items-center gap-4 bg-slate-800 px-4 py-3 rounded-full border border-purple-700 shadow-inner">

                    <button class="text-green-400 text-xl">➕</button>

                    <input
                        type="text"
                        id="message"
                        placeholder="Escribe un mensaje..."
                        class="flex-1 bg-transparent focus:outline-none text-white placeholder-gray-400"
                    >

                    <button onclick="sendMessage()" class="bg-yellow-400 hover:bg-yellow-500 text-white px-6 py-2 rounded-full font-semibold shadow-md transition">
                        Enviar
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

    <div class="bg-slate-900 border border-purple-700 rounded-3xl p-6 w-[400px] shadow-2xl">

        <div class="flex justify-between items-center mb-4">
            <h2 class="text-2xl font-bold text-purple-300">
                Nueva conversación
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
            placeholder="Nombre del grupo (opcional para chat privado)"
            class="w-full mb-4 px-4 py-3 rounded-xl bg-slate-800 text-white border border-purple-700 focus:outline-none"
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
                class="w-full bg-slate-800 border border-purple-700 rounded-xl p-2"
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
            Nueva conversación
        </button>

    </div>

</div>

<script>

    let currentPeerId = null;
    let localStream;
    let peerConnection;
    let callPeerId = null;
    let pendingIce = [];
    async function flushIce() { for (const candidate of pendingIce.splice(0)) await peerConnection.addIceCandidate(candidate); }
    socket.on("connect", () => {
        socket.emit("register_user", userId);
        if (currentRoom) { socket.emit('join_room', currentRoom); loadMessages(currentChatId).catch(e=>notice(e.message)); }
        notice('Conectado');
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

        if(selectedUsers.length > 2 && groupName.trim() === "") {
            alert("Pon nombre al grupo");
            return;
        }

        if(selectedUsers.length < 2) {
            alert("Selecciona al menos un usuario");
            return;
        }

        const formData = new FormData();

        formData.append("type", selectedUsers.length === 2 ? "privado" : "grupo");
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
            notice("Cámara y micrófono requieren HTTPS de confianza (o localhost en el Mac).");
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

        if (!currentPeerId || !socket.connected) return notice('Abre un chat privado y espera conexión.');
        if (peerConnection) return notice('Ya hay una llamada activa.');
        callPeerId = currentPeerId;
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
                    to: callPeerId,
                    candidate: event.candidate
                });
            }
        };

        const offer = await peerConnection.createOffer();
        await peerConnection.setLocalDescription(offer);

        socket.emit("call_user", {
            to: callPeerId,
            from: userId,
            room: currentRoom,
            offer: offer
        });
    }


    socket.on("incoming_call", async (data) => {

        const accept = confirm("📞 Incoming call. Accept?");

        if (!accept || peerConnection) return;
        callPeerId = data.from;

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
                    to: data.from,
                    candidate: event.candidate
                });
            }
        };

        await peerConnection.setRemoteDescription(
            new RTCSessionDescription(data.offer)
        );

        await flushIce();
        const answer = await peerConnection.createAnswer();
        await peerConnection.setLocalDescription(answer);

        socket.emit("call_answer", {
            to: data.from,
            answer: answer
        });
    });

    socket.on("call_answer", async (data) => {
        if (!peerConnection) return;
        await peerConnection.setRemoteDescription(new RTCSessionDescription(data.answer));
        await flushIce();
    });

    socket.on("call_ice_candidate", async (data) => {
        if (data.candidate) {
            if (!peerConnection || !peerConnection.remoteDescription) pendingIce.push(data.candidate);
            else await peerConnection.addIceCandidate(data.candidate);
        }
    });

    socket.on("end_call", () => endCall(false));
    socket.on("disconnect", () => notice("Desconectado. Reconectando…"));
    function endCall(notifyPeer = true) {

        if (peerConnection) {
            peerConnection.close();
            peerConnection = null;
        }

        if (localStream) {
            localStream.getTracks().forEach(track => track.stop());
        }

        if (notifyPeer && callPeerId) socket.emit('end_call', {to:callPeerId});
        callPeerId = null; pendingIce = [];
        document.getElementById('localVideo').srcObject = null;
        document.getElementById('remoteVideo').srcObject = null;
    }



</script>
