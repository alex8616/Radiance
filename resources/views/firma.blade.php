<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Firma digital</title>

    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f6f9;
            margin: 0;
            padding: 30px;
        }

        .card {
            background: #fff;
            width: 100%;
            max-width: 700px;
            margin: 0 auto;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
        }

        h3 {
            margin-bottom: 15px;
            color: #333;
        }

        #canvas {
            border: 2px dashed #ccc;
            width: 100%;
            height: 300px;
            border-radius: 10px;
            background: #fafafa;
        }

        .buttons {
            margin-top: 15px;
            display: flex;
            gap: 10px;
        }

        button {
            border: none;
            padding: 12px 18px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            transition: 0.3s;
        }

        #guardar {
            background: #28a745;
            color: white;
        }

        #guardar:hover {
            background: #218838;
        }

        #limpiar {
            background: #dc3545;
            color: white;
        }

        #limpiar:hover {
            background: #c82333;
        }

        button:disabled {
            background: #aaa !important;
            cursor: not-allowed;
        }

        .msg {
            margin-top: 10px;
            font-size: 14px;
            color: #666;
        }

        .bloqueado {
            opacity: 0.5;
            pointer-events: none;
        }
    </style>
</head>

<body>

<div class="card">
    <h3>✍️ Firma aquí</h3>

    <canvas id="canvas"></canvas>

    <div class="buttons">
        <button id="guardar">Guardar firma</button>
        <button id="limpiar" type="button">Limpiar</button>
    </div>

    <div class="msg" id="msg"></div>
</div>

<script>
    const canvas = document.getElementById('canvas');
    const btnGuardar = document.getElementById('guardar');
    const btnLimpiar = document.getElementById('limpiar');
    const msg = document.getElementById('msg');

    function resizeCanvas() {
        const ratio = Math.max(window.devicePixelRatio || 1, 1);
        canvas.width = canvas.offsetWidth * ratio;
        canvas.height = canvas.offsetHeight * ratio;
        canvas.getContext("2d").scale(ratio, ratio);
    }

    resizeCanvas();

    const signaturePad = new SignaturePad(canvas);

    window.addEventListener("resize", resizeCanvas);

    // 🔒 Si ya firmó
    let yaFirmo = @json($yaFirmo ?? false);

    function bloquearFirma(texto) {
        signaturePad.off();
        btnGuardar.disabled = true;
        btnLimpiar.disabled = true;
        canvas.classList.add("bloqueado");
        msg.innerText = texto;
    }

    if (yaFirmo) {
        bloquearFirma("Ya has firmado anteriormente.");
    }

    // 🧹 Limpiar firma
    btnLimpiar.addEventListener('click', function () {
        signaturePad.clear();
    });

    // 💾 Guardar firma
    btnGuardar.addEventListener('click', function () {

        if (signaturePad.isEmpty()) {
            alert("Por favor firma primero");
            return;
        }

        let firma = signaturePad.toDataURL();

        fetch('/guardar-firma', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                token: '{{ $token }}',
                firma: firma
            })
        })
        .then(res => {
            if (!res.ok) {
                throw new Error("Ya existe una firma");
            }
            return res.json();
        })
        .then(data => {
            alert("Firma guardada correctamente ✅");
            bloquearFirma("Firma registrada correctamente.");
        })
        .catch(err => {
            alert("No se pudo guardar la firma");
        });
    });

</script>

</body>
</html>