<div class="min-h-screen bg-gradient-to-br from-green-900 via-green-800 to-yellow-700 p-8">

    <div class="max-w-4xl mx-auto">

        <div class="text-center mb-10">

            <div class="text-7xl">
                📧⚽
            </div>

            <h1 class="text-5xl font-bold text-white">
                Centro de Comunicaciones FIFA
            </h1>

            <p class="text-green-100 mt-3">
                Envía mensajes oficiales al equipo
            </p>

        </div>

        <div class="bg-gray-900 p-8 rounded-3xl border-4 border-yellow-400">

            <form action="<?= base_url('index.php/smtp/send') ?>"
                  method="POST">

                <div class="mb-4">

                    <label class="text-yellow-400 font-bold">
                        Destinatario
                    </label>

                    <input
                        type="email"
                        name="to"
                        required
                        class="w-full p-4 rounded-xl bg-gray-800 text-white">

                </div>

                <div class="mb-4">

                    <label class="text-yellow-400 font-bold">
                        Asunto
                    </label>

                    <input
                        type="text"
                        name="subject"
                        required
                        class="w-full p-4 rounded-xl bg-gray-800 text-white">

                </div>

                <div class="mb-6">

                    <label class="text-yellow-400 font-bold">
                        Mensaje
                    </label>

                    <textarea
                        name="message"
                        rows="6"
                        class="w-full p-4 rounded-xl bg-gray-800 text-white"></textarea>

                </div>

                <button
                    type="submit"
                    class="bg-yellow-400 text-black px-8 py-3 rounded-full font-bold">

                    📨 Enviar Comunicado

                </button>

            </form>

        </div>

    </div>

</div>