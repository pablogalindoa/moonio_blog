<div class="footer-basic">
    <footer>
        <div class="social">
            <a href="#"><i class="icon ion-social-instagram"></i></a>
            <a href="#"><i class="icon ion-social-snapchat"></i></a>
            <a href="#"><i class="icon ion-social-twitter"></i></a>
            <a href="#"><i class="icon ion-social-facebook"></i></a>
        </div>
        <ul class="list-inline">
            <li class="list-inline-item"><a href="#">- Inicio -</a></li>
            <li class="list-inline-item"><a href="#">- Servicios -</a></li>
            <li class="list-inline-item"><a href="#">- Acerca de Nosotros -</a></li>
            <li class="list-inline-item"><a href="#">- Términos Y Condiciones -</a></li>
            <li class="list-inline-item"><a href="#">- Aviso de Privacidad -</a></li>
        </ul>
        <p class="copyright">= = = M O O N I O © 2025 = = =</p>
    </footer>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.min.js" integrity="sha384-w1Q4orYjBQndcko6MimVbzY0tgp4pWB4lZ7lr30WKz0vr/aWKhXdBNmNb5D92v7s" crossorigin="anonymous"></script>
<script src="//cdn.datatables.net/2.2.2/js/dataTables.min.js"></script>
<script src="https://cdn.datatables.net/2.2.2/js/dataTables.bootstrap5.js"></script>
<script src="https://cdn.datatables.net/staterestore/1.4.1/js/dataTables.stateRestore.js"></script>
<script src="https://cdn.datatables.net/staterestore/1.4.1/js/stateRestore.bootstrap5.js"></script>
<script src="https://cdn.datatables.net/buttons/3.2.1/js/dataTables.buttons.js"></script>
<script src="https://cdn.datatables.net/buttons/3.2.1/js/buttons.bootstrap5.js"></script>

<script>
let table = new DataTable('#dataTable');

</script>

<script>

    $(document).ready(function (){

        $('.confirm-delete').click(function (e){
            e.preventDefault();

            confirmDialog = confirm("Are you sure you want to delete this employee?");
            if(confirmDialog) {

                var id = $(this).val();
                $.ajax({
                    type: "DELETE",
                    url: "employee/confirmdelete/" + id,
                    success: function (response) {
                        alert("Employee Deleted");
                        window.location.reload();
                    }
                })

            }

        });
    
    });

</script>

<script>
document.addEventListener("DOMContentLoaded", () => {

    const btnMyApi = document.getElementById("btnMyApi");
    const btnExternalApi = document.getElementById("btnExternalApi");
    const resultBox = document.getElementById("apiResult");

    /* ============================================
       INTERNAL API (Champions)
       ============================================ */
    if (btnMyApi) {
        btnMyApi.addEventListener("click", function() {

            fetch("<?= base_url('index.php/api/champions'); ?>")
                .then(res => res.json())
                .then(data => {

                    console.log("INTERNAL API DATA:", data);

                    if (!data || !data.data || !Array.isArray(data.data.champions)) {
                        resultBox.innerHTML = "<p>Error: Champions data not found.</p>";
                        resultBox.style.display = "block";
                        return;
                    }

                    const champions = data.data.champions;
                    const mostTitles = data.data.most_titles;

                    let html = "<h3>🏆 Campeones</h3>";
                    html += "<ul style='list-style:none; padding:0;'>";

                    champions.forEach(c => {
                        html += `<li><strong>${c.year}</strong> - ${c.champion}</li>`;
                    });

                    html += "</ul>";
                    html += `<h4>📊 Más Títulos: ${mostTitles.champion} (${mostTitles.titles})</h4>`;

                    resultBox.innerHTML = html;
                    resultBox.style.display = "block";
                })
                .catch(err => {
                    console.error("INTERNAL API ERROR:", err);
                    resultBox.innerHTML = "<p>Error loading internal API.</p>";
                    resultBox.style.display = "block";
                });
        });
    }

    /* ============================================
       EXTERNAL API (Football-Data)
       ============================================ */
    if (btnExternalApi) {
        btnExternalApi.addEventListener("click", function() {

            fetch("<?= base_url('index.php/ExternalApi/worldcup_matches'); ?>")
                .then(res => res.json())
                .then(data => {

                    console.log("EXTERNAL API RAW DATA:", data);

                    if (!data.matches || data.matches.length === 0) {
                        resultBox.innerHTML = "<p>No match data available.</p>";
                        resultBox.style.display = "block";
                        return;
                    }

                    const match = data.matches[0];
                    const competitionName = data.competition?.name || "World Cup";

                    let html = `
                        <h3>🌍 Información del Mundial (API Externa)</h3>
                        <p><strong>${competitionName}</strong></p>

                        <hr>
                        <h4>Primer partido programado:</h4>
                        <p><strong>${match.homeTeam.name}</strong> vs <strong>${match.awayTeam.name}</strong></p>
                        <p><strong>Fecha:</strong> ${match.utcDate}</p>
                        <p><strong>Sede:</strong> ${match.venue || "Por definir"}</p>
                    `;

                    resultBox.innerHTML = html;
                    resultBox.style.display = "block";
                })
                .catch(err => {
                    console.error("EXTERNAL API ERROR:", err);
                    resultBox.innerHTML = "<p>Error loading external API.</p>";
                    resultBox.style.display = "block";
                });
        });
    }

});
</script>

<style>
    .footer-basic {
  padding:40px 0;
  background-color:#ffffff;
  color:#4b4c4d;
}

.footer-basic ul {
  padding:0;
  list-style:none;
  text-align:center;
  font-size:18px;
  line-height:1.6;
  margin-bottom:0;
}

.footer-basic li {
  padding:0 10px;
}

.footer-basic ul a {
  color:inherit;
  text-decoration:none;
  opacity:0.8;
}

.footer-basic ul a:hover {
  opacity:1;
}

.footer-basic .social {
  text-align:center;
  padding-bottom:25px;
}

.footer-basic .social > a {
  font-size:24px;
  width:40px;
  height:40px;
  line-height:40px;
  display:inline-block;
  text-align:center;
  border-radius:50%;
  border:1px solid #ccc;
  margin:0 8px;
  color:inherit;
  opacity:0.75;
}

.footer-basic .social > a:hover {
  opacity:0.9;
}

.footer-basic .copyright {
  margin-top:15px;
  text-align:center;
  font-size:13px;
  color:#aaa;
  margin-bottom:0;
}

</style>

</body>
</html>