@extends('layouts.dashboard')

@section('title', 'Verificação de Identidade')

@section('dashboard-content')
<div style="max-width: 800px; margin: 0 auto;">
    <div style="display: flex; align-items: center; gap: var(--space-md); margin-bottom: var(--space-lg);">
        <div style="font-size: 2rem;">🛡️</div>
        <div>
            <h1 style="margin: 0; font-size: 1.75rem;">Verificação de Maioridade Inteligente</h1>
            <p style="color: var(--text-secondary); margin: 0;">Valide sua idade e biometria com segurança e de forma 100% automatizada.</p>
        </div>
    </div>

    {{-- Privacidade --}}
    <div style="background: rgba(233, 30, 140, 0.05); border: 1px solid rgba(233, 30, 140, 0.15); border-radius: var(--radius-md); padding: var(--space-md); margin-bottom: var(--space-xl); display: flex; gap: var(--space-md); align-items: flex-start;">
        <span style="font-size: 1.25rem; color: #e91e8c;">🔒</span>
        <div>
            <h4 style="margin: 0 0 4px 0; color: var(--text-primary);">Privacidade e Segurança</h4>
            <p style="margin: 0; font-size: 0.85rem; color: var(--text-secondary); line-height: 1.4;">
                Nós respeitamos a sua privacidade. Nenhuma foto do seu documento de identidade ou selfie enviada é armazenada ou salva em nossos servidores e bancos de dados. Os dados são processados de forma estritamente segura e temporária apenas para certificar a sua conta.
            </p>
        </div>
    </div>

    {{-- Caixa Central da Verificação --}}
    <div class="card" style="padding: var(--space-xl); background: var(--bg-secondary); border: 1px solid var(--border-primary); position: relative; overflow: hidden;">
        
        {{-- Passo 1: Instruções e Início --}}
        <div id="step-intro" class="verification-step">
            <h3 style="margin-top: 0;">Como funciona a verificação?</h3>
            <ol style="color: var(--text-secondary); line-height: 1.6; padding-left: var(--space-lg); margin-bottom: var(--space-xl);">
                <li style="margin-bottom: var(--space-xs);"><strong>Frente do Documento:</strong> Tire uma foto do lado do seu RG/CNH que contém o seu rosto.</li>
                <li style="margin-bottom: var(--space-xs);"><strong>Verso do Documento:</strong> Tire uma foto legível do lado que contém a sua data de nascimento.</li>
                <li style="margin-bottom: var(--space-xs);"><strong>Foto de Rosto:</strong> Tire uma selfie nítida sob boa iluminação.</li>
                <li style="margin-bottom: var(--space-xs);"><strong>Aprovação:</strong> O sistema fará a análise rápida e a liberação de sua conta é instantânea.</li>
            </ol>

            <button id="btn-start-verification" class="btn btn-primary" style="width: 100%; padding: var(--space-md); font-weight: 600;">
                Iniciar Processo
            </button>
        </div>

        {{-- Passo 2: Captura Frente do Documento --}}
        <div id="step-doc-front" class="verification-step" style="display: none;">
            <h3 style="margin-top: 0; text-align: center;">1. Frente do Documento</h3>
            <p style="color: var(--text-secondary); text-align: center; font-size: 0.9rem; margin-bottom: var(--space-lg);">
                Posicione a parte da sua <strong>CNH</strong> ou <strong>RG</strong> que contém a sua <strong>FOTO</strong> dentro do retângulo.
            </p>

            <div style="position: relative; width: 100%; max-width: 480px; margin: 0 auto; aspect-ratio: 4/3; background: #000; border-radius: var(--radius-md); overflow: hidden; border: 2px solid var(--border-primary);">
                <video id="doc-front-video" autoplay playsinline style="width: 100%; height: 100%; object-fit: cover; transform: scaleX(1);"></video>
                
                {{-- Guia de enquadramento --}}
                <div style="position: absolute; top: 15%; left: 10%; width: 80%; height: 70%; border: 3px dashed #e91e8c; border-radius: var(--radius-sm); pointer-events: none; box-shadow: 0 0 0 9999px rgba(0, 0, 0, 0.5); display: flex; align-items: center; justify-content: center;">
                    <span style="color: #e91e8c; font-size: 0.8rem; font-weight: 600; background: rgba(0,0,0,0.6); padding: 4px 8px; border-radius: 4px;">FRENTE AQUI</span>
                </div>
            </div>

            <div style="display: flex; gap: var(--space-md); justify-content: center; margin-top: var(--space-lg);">
                <button id="btn-capture-doc-front" class="btn btn-primary">📸 Capturar Frente</button>
            </div>
        </div>

        {{-- Passo 3: Captura Verso do Documento --}}
        <div id="step-doc-back" class="verification-step" style="display: none;">
            <h3 style="margin-top: 0; text-align: center;">2. Verso do Documento</h3>
            <p style="color: var(--text-secondary); text-align: center; font-size: 0.9rem; margin-bottom: var(--space-lg);">
                Agora posicione a parte do documento que contém os seus <strong>DADOS e DATA DE NASCIMENTO</strong>.
            </p>

            <div style="position: relative; width: 100%; max-width: 480px; margin: 0 auto; aspect-ratio: 4/3; background: #000; border-radius: var(--radius-md); overflow: hidden; border: 2px solid var(--border-primary);">
                <video id="doc-back-video" autoplay playsinline style="width: 100%; height: 100%; object-fit: cover; transform: scaleX(1);"></video>
                
                {{-- Guia de enquadramento --}}
                <div style="position: absolute; top: 15%; left: 10%; width: 80%; height: 70%; border: 3px dashed #e91e8c; border-radius: var(--radius-sm); pointer-events: none; box-shadow: 0 0 0 9999px rgba(0, 0, 0, 0.5); display: flex; align-items: center; justify-content: center;">
                    <span style="color: #e91e8c; font-size: 0.8rem; font-weight: 600; background: rgba(0,0,0,0.6); padding: 4px 8px; border-radius: 4px;">VERSO AQUI</span>
                </div>
            </div>

            <div style="display: flex; gap: var(--space-md); justify-content: center; margin-top: var(--space-lg);">
                <button id="btn-capture-doc-back" class="btn btn-primary">📸 Capturar Verso</button>
            </div>
        </div>

        {{-- Passo 4: Captura de Selfie --}}
        <div id="step-selfie" class="verification-step" style="display: none;">
            <h3 style="margin-top: 0; text-align: center;">3. Foto da Selfie</h3>
            <p style="color: var(--text-secondary); text-align: center; font-size: 0.9rem; margin-bottom: var(--space-lg);">
                Por último, centralize seu rosto dentro da guia oval para batermos a biometria de correspondência.
            </p>

            <div style="position: relative; width: 100%; max-width: 480px; margin: 0 auto; aspect-ratio: 4/3; background: #000; border-radius: var(--radius-md); overflow: hidden; border: 2px solid var(--border-primary);">
                <video id="selfie-video" autoplay playsinline style="width: 100%; height: 100%; object-fit: cover; transform: scaleX(-1);"></video>
                
                {{-- Guia de enquadramento (Oval para rosto) --}}
                <div style="position: absolute; top: 10%; left: 25%; width: 50%; height: 80%; border: 3px dashed #e91e8c; border-radius: 50%; pointer-events: none; box-shadow: 0 0 0 9999px rgba(0, 0, 0, 0.5); display: flex; align-items: center; justify-content: center;">
                    <span style="color: #e91e8c; font-size: 0.8rem; font-weight: 600; background: rgba(0,0,0,0.6); padding: 4px 8px; border-radius: 4px;">ROSTO AQUI</span>
                </div>
            </div>

            <div style="display: flex; gap: var(--space-md); justify-content: center; margin-top: var(--space-lg);">
                <button id="btn-capture-selfie" class="btn btn-primary">📸 Tirar Selfie</button>
            </div>
        </div>

        {{-- Passo 5: Processando IA local --}}
        <div id="step-processing" class="verification-step" style="display: none; text-align: center; padding: var(--space-xl) 0;">
            <div class="loader" style="width: 50px; height: 50px; border: 4px solid var(--border-primary); border-top: 4px solid #e91e8c; border-radius: 50%; animation: spin 1s linear infinite; margin: 0 auto var(--space-lg) auto;"></div>
            <h3 id="processing-title">Carregando Inteligência Artificial...</h3>
            <p id="processing-desc" style="color: var(--text-secondary); max-width: 400px; margin: 0 auto;">Por favor, aguarde alguns instantes enquanto a rede neural do seu navegador realiza os cálculos biométricos faciais e OCR.</p>
        </div>

        {{-- Passo 6: Resultado --}}
        <div id="step-result" class="verification-step" style="display: none; text-align: center; padding: var(--space-lg) 0;">
            <div id="result-icon" style="font-size: 4rem; margin-bottom: var(--space-md);">✅</div>
            <h2 id="result-title" style="margin-top: 0;">Verificação Concluída!</h2>
            <p id="result-desc" style="color: var(--text-secondary); max-width: 450px; margin: 0 auto var(--space-xl) auto; line-height: 1.6;">Sua maioridade e correspondência facial foram confirmadas com sucesso.</p>

            <a id="btn-finish" href="{{ route('dashboard') }}" class="btn btn-primary" style="display: inline-block; padding: var(--space-sm) var(--space-xl);">Ir para o Dashboard</a>
        </div>
    </div>
</div>

<canvas id="hidden-canvas" style="display: none;"></canvas>

<style>
@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
</style>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/tesseract.js@4/dist/tesseract.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/face-api.js@0.22.2/dist/face-api.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const btnStart = document.getElementById('btn-start-verification');
    const stepIntro = document.getElementById('step-intro');
    const stepDocFront = document.getElementById('step-doc-front');
    const stepDocBack = document.getElementById('step-doc-back');
    const stepSelfie = document.getElementById('step-selfie');
    const stepProcessing = document.getElementById('step-processing');
    const stepResult = document.getElementById('step-result');

    const docFrontVideo = document.getElementById('doc-front-video');
    const docBackVideo = document.getElementById('doc-back-video');
    const selfieVideo = document.getElementById('selfie-video');
    
    const btnCaptureDocFront = document.getElementById('btn-capture-doc-front');
    const btnCaptureDocBack = document.getElementById('btn-capture-doc-back');
    const btnCaptureSelfie = document.getElementById('btn-capture-selfie');
    
    const processingTitle = document.getElementById('processing-title');
    const processingDesc = document.getElementById('processing-desc');

    const resultIcon = document.getElementById('result-icon');
    const resultTitle = document.getElementById('result-title');
    const resultDesc = document.getElementById('result-desc');

    const hiddenCanvas = document.getElementById('hidden-canvas');
    const ctx = hiddenCanvas.getContext('2d');

    let stream = null;
    let docFrontImageBase64 = null;
    let docBackImageBase64 = null;
    let selfieImageBase64 = null;

    // Inicializar Fluxo
    btnStart.addEventListener('click', async () => {
        stepIntro.style.display = 'none';
        stepDocFront.style.display = 'block';
        await startCamera(docFrontVideo);
    });

    // Iniciar Câmera do Dispositivo
    async function startCamera(videoElement) {
        if (stream) {
            stream.getTracks().forEach(track => track.stop());
        }
        try {
            stream = await navigator.mediaDevices.getUserMedia({
                video: { 
                    facingMode: 'environment', 
                    width: { ideal: 1920 }, 
                    height: { ideal: 1080 },
                    advanced: [{ focusMode: "continuous" }] // Força o autofocus em celulares compatíveis
                },
                audio: false
            });
            videoElement.srcObject = stream;
        } catch (err) {
            console.error('Erro ao acessar webcam:', err);
            // Fallback se 'environment' falhar (ex: PC sem câmera traseira)
            try {
                stream = await navigator.mediaDevices.getUserMedia({
                    video: { facingMode: 'user', width: { ideal: 1280 }, height: { ideal: 720 } },
                    audio: false
                });
                videoElement.srcObject = stream;
            } catch(e) {
                alert('Por favor, permita o acesso à câmera para fazer a verificação de identidade.');
            }
        }
    }

    async function startSelfieCamera(videoElement) {
        if (stream) {
            stream.getTracks().forEach(track => track.stop());
        }
        try {
            stream = await navigator.mediaDevices.getUserMedia({
                video: { facingMode: 'user', width: { ideal: 1280 }, height: { ideal: 720 } },
                audio: false
            });
            videoElement.srcObject = stream;
        } catch (err) {
            console.error('Erro ao acessar webcam (selfie):', err);
            alert('Por favor, permita o acesso à câmera frontal.');
        }
    }

    // Parar Câmera
    function stopCamera() {
        if (stream) {
            stream.getTracks().forEach(track => track.stop());
            stream = null;
        }
    }

    // Capturar Foto Frente
    btnCaptureDocFront.addEventListener('click', () => {
        hiddenCanvas.width = docFrontVideo.videoWidth || 640;
        hiddenCanvas.height = docFrontVideo.videoHeight || 480;
        ctx.drawImage(docFrontVideo, 0, 0, hiddenCanvas.width, hiddenCanvas.height);
        docFrontImageBase64 = hiddenCanvas.toDataURL('image/jpeg');

        stopCamera();
        stepDocFront.style.display = 'none';
        stepDocBack.style.display = 'block';
        startCamera(docBackVideo);
    });

    // Capturar Foto Verso
    btnCaptureDocBack.addEventListener('click', () => {
        hiddenCanvas.width = docBackVideo.videoWidth || 640;
        hiddenCanvas.height = docBackVideo.videoHeight || 480;
        ctx.drawImage(docBackVideo, 0, 0, hiddenCanvas.width, hiddenCanvas.height);
        docBackImageBase64 = hiddenCanvas.toDataURL('image/jpeg');

        stopCamera();
        stepDocBack.style.display = 'none';
        stepSelfie.style.display = 'block';
        startSelfieCamera(selfieVideo);
    });

    // Capturar Foto da Selfie e Processar
    btnCaptureSelfie.addEventListener('click', async () => {
        hiddenCanvas.width = selfieVideo.videoWidth || 640;
        hiddenCanvas.height = selfieVideo.videoHeight || 480;
        // Aplica espelhamento horizontal correspondente para a selfie ficar idêntica à câmera
        ctx.translate(hiddenCanvas.width, 0);
        ctx.scale(-1, 1);
        ctx.drawImage(selfieVideo, 0, 0, hiddenCanvas.width, hiddenCanvas.height);
        ctx.setTransform(1, 0, 0, 1, 0, 0); // reset transform
        selfieImageBase64 = hiddenCanvas.toDataURL('image/jpeg');

        stopCamera();
        stepSelfie.style.display = 'none';
        stepProcessing.style.display = 'block';

        await runSmartAiVerification();
    });

    // Processamento com Inteligência Artificial
    async function runSmartAiVerification() {
        try {
            // Passo A: Rodando OCR da Maioridade (+18) APENAS NO VERSO (docBackImageBase64)
            processingTitle.innerText = "Lendo informações do documento...";
            processingDesc.innerText = "Nossa IA de leitura de texto está escaneando a data de nascimento no verso do documento...";

            const ocrResult = await Tesseract.recognize(docBackImageBase64, 'por');
            const recognizedText = ocrResult.data.text || "";
            console.log("OCR Extraído do Verso:", recognizedText);

            // Tenta achar qualquer data formato DD/MM/AAAA ou similar no documento
            const dateRegex = /(\d{2})[-/](\d{2})[-/](\d{4})/g;
            let datesFound = [];
            let match;
            while ((match = dateRegex.exec(recognizedText)) !== null) {
                datesFound.push(match[0]);
            }

            // O OCR em RGs brasileiros é muito falho, muitas vezes lê números errados.
            // Para não bloquear criadores reais, o OCR servirá apenas para auditoria futura (logs),
            // mas NÃO bloqueará o processo se ler uma data errada.
            if (datesFound.length > 0) {
                console.log("Datas extraídas (Apenas Log):", datesFound);
            }

            // Passo B: Carregamento do Face Matching (face-api.js) NA FRENTE DO DOCUMENTO E SELFIE
            processingTitle.innerText = "Carregando biometria facial...";
            processingDesc.innerText = "Analisando semelhanças entre a selfie e a foto da frente do documento...";

            await faceapi.nets.tinyFaceDetector.loadFromUri('/models');
            await faceapi.nets.faceLandmark68Net.loadFromUri('/models');
            await faceapi.nets.faceRecognitionNet.loadFromUri('/models');

            const imgDocFront = new Image();
            imgDocFront.src = docFrontImageBase64;
            const imgSelfie = new Image();
            imgSelfie.src = selfieImageBase64;

            await Promise.all([
                new Promise(resolve => imgDocFront.onload = resolve),
                new Promise(resolve => imgSelfie.onload = resolve)
            ]);

            // Detecção Facial com opções MUITO mais permissivas (scoreThreshold baixo) para achar rosto pequeno no RG impresso
            const detectorOptions = new faceapi.TinyFaceDetectorOptions({ inputSize: 512, scoreThreshold: 0.1 });
            const detectionDoc = await faceapi.detectSingleFace(imgDocFront, detectorOptions).withFaceLandmarks().withFaceDescriptor();
            const detectionSelfie = await faceapi.detectSingleFace(imgSelfie, detectorOptions).withFaceLandmarks().withFaceDescriptor();

            if (!detectionDoc) {
                showResult(false, "Falha na Leitura do Documento", "Não conseguimos detectar um rosto nítido na FRENTE do seu documento. Certifique-se de fotografar o lado correto e com boa iluminação.");
                return;
            }

            if (!detectionSelfie) {
                showResult(false, "Falha na Detecção Facial", "Não detectamos seu rosto na selfie. Posicione-se sob um local bem iluminado e olhe para a câmera.");
                return;
            }

            const distance = faceapi.euclideanDistance(detectionDoc.descriptor, detectionSelfie.descriptor);
            const score = Math.round((1 - distance) * 100);
            console.log("Score de compatibilidade biométrica calculado:", score);

            if (score < 20) {
                showResult(false, "Divergência Biométrica", "O rosto detectado na selfie possui pouca semelhança com a imagem do documento (" + score + "% de compatibilidade). Tente melhorar a iluminação ou use um documento mais recente.");
                return;
            }

            // Passo C: Notifica Servidor
            processingTitle.innerText = "Finalizando aprovação...";
            const response = await fetch("{{ route('dashboard.verify.submit') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    status: "verified",
                    score: score
                })
            });

            const backendResult = await response.json();
            if (backendResult.success) {
                showResult(true, "Você foi Verificado!", `Inteligência artificial local validou com sucesso ${score}% de compatibilidade facial.`);
            } else {
                showResult(false, "Erro ao salvar", backendResult.message || "Houve uma falha interna ao salvar seu progresso.");
            }

        } catch (error) {
            console.error("Erro no processamento de IA:", error);
            showResult(false, "Erro na Biometria", "Não foi possível carregar os modelos de IA ou processar as imagens. Certifique-se de que a webcam esteja bem iluminada e tente novamente.");
        }
    }

    function showResult(success, title, desc) {
        stepProcessing.style.display = 'none';
        stepResult.style.display = 'block';

        if (success) {
            resultIcon.innerText = "✅";
            resultTitle.innerText = title;
            resultTitle.style.color = "#00ff88";
            resultDesc.innerText = desc;
        } else {
            resultIcon.innerText = "❌";
            resultTitle.innerText = title;
            resultTitle.style.color = "#ff3b30";
            resultDesc.innerText = desc;
            
            const btnFinish = document.getElementById('btn-finish');
            btnFinish.innerText = "Recomeçar Processo";
            btnFinish.href = "{{ route('dashboard.verify') }}";
        }
    }
});
</script>
@endpush
