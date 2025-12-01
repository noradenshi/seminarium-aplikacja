<!DOCTYPE html>
<html lang="pl">

<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta charset="UTF-8">
    <title>IRZ</title>
    <style>
        .center {text-align:center}

        div {width:410px; height:209px}

        div * {
        display:block; width:100%; margin:0; padding:0;
        box-sizing:border-box; font-family:Arial; font-size:10pt
        }

        p {
        height:30px; padding:0.8ex 0; text-align:center; background-color:#f3f3ee;
        border-style:solid; border-width:1px; border-color:gray; font-weight:bold
        }

        textarea {
        height:145px; resize:none; background-color:white; color:black;
        border-style:solid; border-width:1px; border-top-width:0; border-color:gray;
        }

        button {height:32px; margin-top:2px; font-weight:bold}
    </style>
</head>

<body>
    <h2 class="center">IRZ</h2>

    <div class="container">
        <p id="head">Ładowanie danych...</p>
        <textarea class="recordwrap" id="records-area" readonly></textarea>
        <button id="draw-btn" type="button">Losuj zestaw</button>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const recordsArea = document.getElementById('records-area');
            const headLabel = document.getElementById('head');
            const drawBtn = document.getElementById('draw-btn');

            function updateView(data) {
                if (!data) return;

                // ZMIANA: Teraz wyświetlamy zawsze maksymalną (początkową) liczbę zadań
                headLabel.innerHTML = `Excel: ${data.maxExcel} &nbsp;Java: ${data.maxJava}`;

                if (data.records && data.records.length > 0) {
                    recordsArea.value = data.records.join('\n');
                    recordsArea.scrollTop = recordsArea.scrollHeight;
                } else {
                    recordsArea.value = '';
                }

                if (!data.canDraw) {
                    drawBtn.disabled = true;
                    drawBtn.textContent = "Brak zadań do wylosowania";
                } else {
                    drawBtn.disabled = false;
                    drawBtn.textContent = "Losuj zestaw";
                }
            }

            async function communicateWithServer(action = null) {
                try {
                    const options = {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        }
                    };

                    if (action) {
                        options.body = JSON.stringify({ action: action });
                    }

                    const response = await fetch('backend.php', options);

                    if (!response.ok) {
                        throw new Error(`Błąd serwera: ${response.status}`);
                    }

                    const data = await response.json();
                    updateView(data);

                } catch (error) {
                    console.error('Szczegóły błędu:', error);
                    headLabel.innerText = "Błąd połączenia. Sprawdź konsolę (F12).";
                    headLabel.style.color = "red";
                }
            }

            communicateWithServer('reset');

            drawBtn.addEventListener('click', (e) => {
                e.preventDefault();
                communicateWithServer('draw');
            });
        });
    </script>

</body>

</html>