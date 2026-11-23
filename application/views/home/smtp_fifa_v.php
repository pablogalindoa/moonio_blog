<div class="min-h-screen bg-gradient-to-br from-slate-950 via-indigo-950 to-purple-950 p-8">

    <div class="max-w-4xl mx-auto">

        <div class="text-center mb-10">

            <div class="text-7xl">
                📧
            </div>

            <h1 class="text-5xl font-bold text-white">
                Centro de Comunicaciones
            </h1>

            <p class="text-indigo-200 mt-3">
                Envía mensajes intergalácticos
            </p>

        </div>

        <div class="bg-gray-900 p-8 rounded-3xl border-2 border-purple-500">

            <form action="<?= base_url('index.php/smtp/send') ?>"
                  method="POST">

                <div class="mb-4">

                    <label class="text-purple-300 font-bold block mb-2">
                        Destinatario
                    </label>

                    <select name="to" required class="w-full p-4 rounded-xl bg-gray-800 text-white">
                        <option value="">Selecciona un usuario registrado</option>
                        <?php foreach ($recipients as $recipient): ?>
                        <option value="<?= html_escape($recipient->email) ?>"><?= html_escape($recipient->first_name.' '.$recipient->last_name.' — '.$recipient->email) ?></option>
                        <?php endforeach; ?>
                    </select>

                </div>

                <div class="mb-4">

                    <label class="text-purple-300 font-bold block mb-2">
                        Asunto
                    </label>

                    <input
                        type="text"
                        name="subject"
                        required
                        class="w-full p-4 rounded-xl bg-gray-800 text-white border-2 border-purple-500 focus:border-blue-400 focus:outline-none">

                </div>

                <div class="mb-6">

                    <label class="text-purple-300 font-bold block mb-2">
                        Mensaje
                    </label>

                    <textarea
                        name="message"
                        rows="6"
                        class="w-full p-4 rounded-xl bg-gray-800 text-white border-2 border-purple-500 focus:border-blue-400 focus:outline-none"></textarea>

                </div>

                <button
                    type="submit"
                    class="bg-gradient-to-r from-purple-500 to-blue-500 text-white px-8 py-3 rounded-full font-bold hover:opacity-90">

                    📨 Enviar Comunicado

                </button>

            </form>

        </div>

    </div>

</div>