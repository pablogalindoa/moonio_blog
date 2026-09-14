<div class="max-w-xl mx-auto bg-white p-8 rounded shadow">

    <h2 class="text-2xl font-bold mb-6 text-center">Crear Página de Mundial</h2>

    <form action="<?= base_url('worldcup/store'); ?>" 
          method="POST" 
          enctype="multipart/form-data">

        <label class="block mb-2 font-semibold">Año del Mundial</label>
        <input type="number" name="year" required
               class="w-full p-2 border rounded mb-4">

        <label class="block mb-2 font-semibold">País Anfitrión</label>
        <input type="text" name="host" required
               class="w-full p-2 border rounded mb-4">

        <label class="block mb-2 font-semibold">Ganador</label>
        <input type="text" name="winner" required
               class="w-full p-2 border rounded mb-4">

        <label class="block mb-2 font-semibold">Imagen de Fondo</label>
        <input type="file" name="background_img" 
               class="w-full p-2 border rounded mb-4">

        <button type="submit"
                class="w-full bg-blue-600 hover:bg-blue-800 text-white p-3 rounded">
            Crear Página
        </button>

    </form>

</div>
