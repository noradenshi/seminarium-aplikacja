<!DOCTYPE html>
<html lang="pl">

<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta charset="UTF-8">
    <title>IRZ</title>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <style>
        .center {
            text-align: center
        }

        div {
            width: 410px;
            height: 209px
        }

        div * {
            display: block;
            width: 100%;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial;
            font-size: 10pt
        }

        p {
            height: 30px;
            padding: 0.8ex 0;
            text-align: center;
            background-color: #f3f3ee;
            border-style: solid;
            border-width: 1px;
            border-color: gray;
            font-weight: bold
        }

        textarea {
            height: 145px;
            resize: none;
            background-color: white;
            color: black;
            border-style: solid;
            border-width: 1px;
            border-top-width: 0;
            border-color: gray;
        }

        button {
            height: 32px;
            margin-top: 2px;
            font-weight: bold
        }
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
        $(document).ready(function() {
            const recordsArea = $('#records-area');
            const headLabel = $('#head');
            const drawBtn = $('#draw-btn');

            function updateView(data) {
                if (!data) return;

                headLabel.html(`Excel: ${data.maxExcel} &nbsp;Java: ${data.maxJava}`);

                if (data.records && data.records.length > 0) {
                    recordsArea.val(data.records.join('\n'));
                    recordsArea.scrollTop(recordsArea[0].scrollHeight);
                } else {
                    recordsArea.val('');
                }
            }

            function communicateWithServer(action = null) {
                $.ajax({
                    url: 'backend.php',
                    type: 'POST',
                    contentType: 'application/json',
                    dataType: 'json',
                    data: action ? JSON.stringify({
                        action: action
                    }) : null,

                    success: function(data) {
                        updateView(data);
                    },

                    error: function(xhr, status, error) {
                        console.error('Szczegóły błędu:', status, error);
                        headLabel.text("Błąd połączenia. Sprawdź konsolę (F12).");
                        headLabel.css("color", "red");
                    }
                });
            }

            // reset przy starcie
            communicateWithServer('reset');

            drawBtn.on('click', function(e) {
                e.preventDefault();
                communicateWithServer('draw');
            });
        });
    </script>

</body>

</html>
