<?php
/**
 * HOMEPAGE CMS - Premiata Erboristeria Ceccato
 * Layout IDENTICO alla versione HTML
 */
require_once 'config.php';

// Carica prodotti in evidenza
$stmt = $pdo->query("SELECT * FROM prodotti WHERE in_evidenza = TRUE AND attivo = TRUE ORDER BY id");
$prodotti = $stmt->fetchAll();

// Carica categorie
$stmt = $pdo->query("SELECT * FROM categorie ORDER BY ordine");
$categorie = $stmt->fetchAll();

// Carica slider
$stmt = $pdo->query("SELECT * FROM slider WHERE attivo = TRUE ORDER BY ordine");
$slides = $stmt->fetchAll();

// Carica video
$stmt = $pdo->query("SELECT * FROM video WHERE attivo = TRUE ORDER BY ordine");
$video = $stmt->fetchAll();

// Carica fornitori
$stmt = $pdo->query("SELECT * FROM fornitori WHERE attivo = TRUE ORDER BY ordine");
$fornitori = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $sito['nome']; ?></title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: #f5f5f0;
        }

        .top-banner {
            background: linear-gradient(135deg, #8B7355 0%, #A0826D 100%);
            color: white;
            padding: 12px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .search-bar input {
            padding: 6px 12px;
            border: none;
            border-radius: 20px;
            width: 250px;
        }

        header {
            background: linear-gradient(135deg, #f4d03f 0%, #c5b358 100%);
            padding: 30px;
            text-align: center;
        }

        h1 {
            color: #2d5016;
            font-size: 42px;
            white-space: nowrap;
        }

        nav {
            background: linear-gradient(135deg, #4a7c23 0%, #5a9129 100%);
            position: sticky;
            top: 0;
            z-index: 999;
        }

        .nav-container { display: flex; justify-content: center; align-items: center; padding: 10px; flex-wrap: wrap; }
        .nav-item { position: relative; display: flex; align-items: center; }
        .nav-button { background: transparent; color: white; border: none; padding: 14px 18px; font-size: 14px; font-weight: 600; cursor: pointer; border-radius: 8px; margin: 0 3px; white-space: nowrap; text-decoration: none; display: inline-block; vertical-align: middle; line-height: 1; }
        .nav-button:hover { background: rgba(255,255,255,0.2); }

        .dropdown {
            display: none;
            position: absolute;
            top: 100%;
            left: 50%;
            transform: translateX(-50%);
            background: white;
            min-width: 200px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.2);
            padding-top: 10px;
            z-index: 1001;
        }
        .dropdown::before { content: ''; position: absolute; top: -10px; left: 0; right: 0; height: 10px; }
        .nav-item:hover .dropdown { display: block; }
        .dropdown a { display: block; padding: 12px 20px; color: #2d5016; text-decoration: none; font-size: 13px; }
        .dropdown a:hover { background: #f4d03f; }

        .slider-container {
            width: 100%;
            height: 400px;
            position: relative;
            background: #000;
            overflow: hidden;
        }
        .slider { display: flex; height: 100%; transition: transform 0.5s ease; }
        .slide { min-width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; color: white; font-size: 32px; }
        .slider-arrow { position: absolute; top: 50%; transform: translateY(-50%); background: rgba(255,255,255,0.8); border: none; font-size: 24px; padding: 15px 20px; cursor: pointer; border-radius: 5px; z-index: 10; }
        .slider-arrow.prev { left: 20px; }
        .slider-arrow.next { right: 20px; }

        main {
            max-width: 1400px;
            margin: 0 auto;
            padding: 40px;
        }

        .hero-section {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 30px;
            margin-bottom: 40px;
        }

        .image-box {
            aspect-ratio: 1;
            background: rgba(244,208,63,0.2);
            border: 3px solid #4a7c23;
            border-radius: 15px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            font-weight: bold;
            color: #4a7c23;
        }
        .placeholder-icon { font-size: 80px; margin-bottom: 15px; }

        .products-section {
            background: white;
            border-radius: 20px;
            padding: 40px;
            margin: 40px 0;
        }
        .products-section h2 {
            color: #2d5016;
            font-size: 32px;
            text-align: center;
            margin-bottom: 30px;
        }
        .products-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 25px;
        }

        .product-card {
            background: white;
            border: 3px solid #f4d03f;
            border-radius: 12px;
            overflow: hidden;
            height: 380px;
            display: flex;
            flex-direction: column;
        }
        .product-card:hover { transform: translateY(-3px); box-shadow: 0 6px 20px rgba(0,0,0,0.15); }
        .product-image {
            height: 160px;
            background: rgba(244,208,63,0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 60px;
            position: relative;
            flex-shrink: 0;
        }
        .product-badge {
            position: absolute;
            top: 8px;
            right: 8px;
            background: #e74c3c;
            color: white;
            padding: 4px 10px;
            border-radius: 15px;
            font-size: 10px;
            font-weight: bold;
        }
        .product-badge.new { background: #27ae60; }
        .product-info {
            padding: 15px;
            display: flex;
            flex-direction: column;
            height: 220px;
        }
        .product-name { color: #2d5016; font-size: 16px; font-weight: bold; height: 40px; overflow: hidden; line-height: 1.2; margin-bottom: 6px; }
        .product-description { color: #666; font-size: 12px; height: 32px; overflow: hidden; margin-bottom: 10px; line-height: 1.3; }
        .product-price { display: flex; align-items: baseline; gap: 8px; margin-bottom: 12px; }
        .price-current { color: #2d5016; font-size: 22px; font-weight: bold; }
        .price-old { color: #999; font-size: 14px; text-decoration: line-through; }
        .product-actions { display: flex; gap: 8px; margin-top: auto; }
        .btn-add-cart { flex: 1; background: #4a7c23; color: white; border: none; padding: 10px; border-radius: 6px; font-weight: 600; font-size: 12px; cursor: pointer; }
        .btn-wishlist { background: white; border: 2px solid #4a7c23; color: #4a7c23; padding: 8px 12px; border-radius: 6px; cursor: pointer; font-size: 16px; }

        .video-section {
            background: white;
            border-radius: 20px;
            padding: 40px;
            margin: 40px 0;
        }
        .video-section h2 { color: #2d5016; font-size: 32px; text-align: center; margin-bottom: 30px; }
        .video-layout { display: flex; gap: 30px; }
        .video-container { width: 640px; height: 480px; background: #000; border-radius: 15px; }
        .video-container video { width: 100%; height: 100%; }
        .video-controls { display: flex; justify-content: center; gap: 15px; margin-top: 20px; }
        .video-btn { background: #4a7c23; color: white; border: none; padding: 12px 25px; font-size: 14px; font-weight: 600; cursor: pointer; border-radius: 8px; }
        .video-playlist { flex: 1; background: rgba(244,208,63,0.1); border-radius: 15px; padding: 20px; max-height: 480px; overflow-y: auto; }
        .playlist-item { background: white; padding: 15px; margin-bottom: 10px; border-radius: 8px; cursor: pointer; }
        .playlist-item:hover { background: #f4d03f; }
        .playlist-item.active { background: #4a7c23; color: white; }

        .suppliers-section {
            background: #f5f5f0;
            border-radius: 20px;
            padding: 40px;
            margin: 40px 0;
        }
        .suppliers-section h3 { text-align: center; color: #2d5016; font-size: 28px; margin-bottom: 30px; }
        .suppliers-logos { display: grid; grid-template-columns: repeat(6, 1fr); gap: 15px; }
        .supplier-logo {
            width: 150px;
            height: 150px;
            background: white;
            border: 2px solid #f4d03f;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 8px;
            font-size: 13px;
            font-weight: 600;
            color: #5a7c3a;
        }
        .supplier-logo:hover { box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        .supplier-logo span:first-child { font-size: 20px; }

        .cart-icon {
            position: fixed;
            top: 80px;
            right: 30px;
            background: #f4d03f;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            cursor: pointer;
            box-shadow: 0 4px 15px rgba(0,0,0,0.3);
            z-index: 1001;
        }
        .cart-count {
            position: absolute;
            top: -5px;
            right: -5px;
            background: #e74c3c;
            color: white;
            width: 26px;
            height: 26px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
            font-weight: bold;
        }

        footer {
            background: #2d5016;
            color: white;
            padding: 50px 40px 30px;
        }
        .footer-content {
            max-width: 1400px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 25px;
            margin-bottom: 30px;
            align-items: start;
        }
        .footer-column { display: flex; flex-direction: column; }
        .footer-column h4 { font-size: 16px; margin-bottom: 15px; color: #f4d03f; padding-bottom: 10px; border-bottom: 2px solid rgba(244,208,63,0.3); }
        .footer-column h2 { font-size: 16px; margin-bottom: 15px; color: #f4d03f; padding-bottom: 10px; border-bottom: 2px solid rgba(244,208,63,0.3); }
        .footer-column p, .footer-column a { color: white; font-size: 13px; line-height: 1.6; text-decoration: none; display: block; margin-bottom: 6px; }
        .footer-column a:hover { color: #f4d03f; }
        .footer-logo { font-size: 18px; font-weight: bold; color: #f4d03f; margin-bottom: 12px; }
        .footer-bottom { text-align: center; padding-top: 20px; border-top: 1px solid rgba(255,255,255,0.1); font-size: 13px; }

        .newsletter-section { background: rgba(244,208,63,0.1); border-radius: 10px; padding: 15px; }
        .newsletter-section h2 { border-bottom: none; padding-bottom: 5px; }
        .newsletter-section p { font-size: 12px; margin-bottom: 12px; }
        .newsletter-form { display: flex; flex-direction: column; gap: 8px; }
        .newsletter-input { padding: 10px; border: none; border-radius: 6px; font-size: 13px; }
        .newsletter-btn { background: #f4d03f; color: #2d5016; border: none; padding: 10px; border-radius: 6px; font-weight: bold; cursor: pointer; font-size: 13px; }
        .newsletter-btn:hover { background: #fff; }

        .chat-column { background: rgba(244,208,63,0.1); border-radius: 10px; padding: 15px; }
        .chat-column h2 { border-bottom: none; padding-bottom: 5px; }
        .footer-chat { background: white; border-radius: 8px; overflow: hidden; height: 200px; display: flex; flex-direction: column; }
        .chat-messages { flex: 1; padding: 12px; overflow-y: auto; background: #f9f9f9; }
        .chat-message { padding: 8px 12px; border-radius: 12px; margin-bottom: 8px; font-size: 12px; }
        .chat-message.bot { background: #e8f5e9; color: #2d5016; }
        .chat-message.user { background: #4a7c23; color: white; margin-left: 20%; }
        .chat-input-area { display: flex; padding: 8px; background: white; border-top: 1px solid #eee; gap: 6px; }
        .chat-input { flex: 1; padding: 8px; border: 1px solid #ddd; border-radius: 15px; font-size: 12px; }
        .chat-send { background: #4a7c23; color: white; border: none; padding: 8px 12px; border-radius: 15px; cursor: pointer; font-weight: 600; font-size: 12px; }

        @media (max-width: 1200px) { .products-grid { grid-template-columns: repeat(3, 1fr); } }
        @media (max-width: 900px) { 
            .products-grid { grid-template-columns: repeat(2, 1fr); } 
            .suppliers-logos { grid-template-columns: repeat(4, 1fr); }
            .footer-content { grid-template-columns: repeat(2, 1fr); }
        }
        @media (max-width: 600px) { 
            .products-grid { grid-template-columns: 1fr; } 
            .hero-section { grid-template-columns: 1fr; }
            .suppliers-logos { grid-template-columns: repeat(2, 1fr); }
            .footer-content { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    <div class="top-banner">
        <div>📦 Spedizione gratuita dai <?php echo $sito['spedizione_gratis']; ?>€</div>
        <div style="display: flex; gap: 20px; align-items: center;">
            <a href="accedi.html" style="color: white; text-decoration: none;">Registrati / Accedi</a>
            <div class="search-bar">
                <input type="text" placeholder="Cerca nel sito...">
            </div>
        </div>
    </div>

    <header>
        <h1>🌿 <?php echo $sito['nome']; ?> 🌿</h1>
    </header>

    <nav>
        <div class="nav-container">
            <div class="nav-item"><a href="index.php" class="nav-button">Home</a></div>
            <div class="nav-item">
                <button class="nav-button">Chi Siamo</button>
                <div class="dropdown">
                    <a href="la-nostra-storia.html">La Nostra Storia</a>
                    <a href="il-nostro-team.html">Il Nostro Team</a>
                    <a href="i-nostri-negozi.html">I Nostri Negozi</a>
                    <a href="contattaci.html">Contattaci</a>
                </div>
            </div>
            <div class="nav-item">
                <button class="nav-button">Prodotti</button>
                <div class="dropdown">
                    <?php foreach ($categorie as $cat): ?>
                    <a href="<?php echo $cat['slug']; ?>.html"><?php echo $cat['icona']; ?> <?php echo $cat['nome']; ?></a>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="nav-item"><a href="offerte.html" class="nav-button">🏷️ Offerte</a></div>
            <div class="nav-item"><a href="prenota-consulenza.html" class="nav-button">📅 Prenota</a></div>
            <div class="nav-item"><a href="gift-card.html" class="nav-button">🎁 Gift Card</a></div>
        </div>
    </nav>

    <!-- Slider -->
    <div class="slider-container">
        <div class="slider" id="slider">
            <?php foreach ($slides as $slide): ?>
            <div class="slide" style="background: <?php echo $slide['colore_bg']; ?>;">
                <div style="text-align: center;">
                    <h2 style="font-size: 48px; margin-bottom: 15px;"><?php echo $slide['titolo']; ?></h2>
                    <p style="font-size: 24px;"><?php echo $slide['sottotitolo']; ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <button class="slider-arrow prev" onclick="moveSlide(-1)">❮</button>
        <button class="slider-arrow next" onclick="moveSlide(1)">❯</button>
    </div>

    <main>
        <!-- Hero Section -->
        <div class="hero-section">
            <div class="image-box">
                <div class="placeholder-icon">🌿</div>
                <div>PRODOTTI BIO</div>
            </div>
            <div class="image-box">
                <div class="placeholder-icon">💎</div>
                <div>CRISTALLI</div>
            </div>
            <div class="image-box">
                <div class="placeholder-icon">✨</div>
                <div>NOVITÀ</div>
            </div>
        </div>

        <!-- Prodotti -->
        <div class="products-section">
            <h2>🛒 I Nostri Prodotti</h2>
            <div class="products-grid">
                <?php foreach ($prodotti as $p): ?>
                <div class="product-card">
                    <div class="product-image">
                        <?php echo $p['icona']; ?>
                        <?php if ($p['badge']): ?>
                        <div class="product-badge <?php echo $p['badge'] == 'NUOVO' ? 'new' : ''; ?>"><?php echo $p['badge']; ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="product-info">
                        <div class="product-name"><?php echo $p['nome']; ?></div>
                        <div class="product-description"><?php echo $p['descrizione']; ?></div>
                        <div class="product-price">
                            <span class="price-current">€<?php echo number_format($p['prezzo'], 2); ?></span>
                            <?php if ($p['prezzo_originale']): ?>
                            <span class="price-old">€<?php echo number_format($p['prezzo_originale'], 2); ?></span>
                            <?php endif; ?>
                        </div>
                        <div class="product-actions">
                            <button class="btn-add-cart" onclick="addToCart(<?php echo $p['id']; ?>)">Aggiungi</button>
                            <button class="btn-wishlist">❤️</button>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Video Section -->
        <div class="video-section">
            <h2>🎥 I Nostri Video</h2>
            <div class="video-layout">
                <div>
                    <div class="video-container">
                        <video id="videoPlayer" controls>
                            <source id="videoSource" src="<?php echo $video[0]['url'] ?? ''; ?>" type="video/mp4">
                        </video>
                    </div>
                    <div class="video-controls">
                        <button class="video-btn" onclick="previousVideo()">⏮️ Precedente</button>
                        <button class="video-btn" onclick="playPauseVideo()">▶️ Play/Pausa</button>
                        <button class="video-btn" onclick="nextVideo()">Prossimo ⏭️</button>
                    </div>
                </div>
                <div class="video-playlist" id="playlistContainer">
                    <?php foreach ($video as $i => $v): ?>
                    <div class="playlist-item <?php echo $i === 0 ? 'active' : ''; ?>" onclick="loadVideo(<?php echo $i; ?>)"><?php echo ($i+1) . '. ' . $v['titolo']; ?></div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- Fornitori -->
        <div class="suppliers-section">
            <h3>🏭 I Nostri Fornitori</h3>
            <div class="suppliers-logos">
                <?php foreach ($fornitori as $f): ?>
                <div class="supplier-logo">
                    <span><?php echo $f['icona']; ?></span>
                    <span><?php echo $f['nome']; ?></span>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </main>

    <!-- Carrello fisso -->
    <a href="carrello.html" class="cart-icon">
        🛒
        <span class="cart-count" id="cartCount">0</span>
    </a>

    <footer>
        <div class="footer-content">
            <div class="footer-column">
                <div class="footer-logo">🌿 <?php echo $sito['nome']; ?></div>
                <p><strong>Titolare:</strong> <?php echo $sito['titolare']; ?></p>
                <p><strong>Sede:</strong><br><?php echo $sito['indirizzo']; ?><br><?php echo $sito['citta']; ?></p>
                <p><strong>Tel:</strong> <?php echo $sito['telefono']; ?><br><strong>Email:</strong> <?php echo $sito['email']; ?></p>
                <p><strong>P.IVA:</strong> <?php echo $sito['piva']; ?></p>
            </div>

            <div class="footer-column">
                <h4>Informazioni Legali</h4>
                <a href="termini-condizioni.html">Termini e Condizioni</a>
                <a href="condizioni-vendita.html">Condizioni Generali di Vendita</a>
                <a href="diritto-recesso.html">Diritto di Recesso (14 giorni)</a>
                <a href="spedizioni-consegne.html">Spedizioni e Consegne</a>
                <a href="metodi-pagamento.html">Metodi di Pagamento</a>
                <a href="privacy-policy.html">Privacy Policy (GDPR)</a>
                <a href="cookie-policy.html">Cookie Policy</a>
            </div>

            <div class="footer-column">
                <h4>Seguici sui Social</h4>
                <a href="https://facebook.com" target="_blank">📘 Facebook</a>
                <a href="https://tiktok.com" target="_blank">🎵 TikTok</a>
                <a href="https://instagram.com" target="_blank">📷 Instagram</a>
                <a href="https://ebay.it" target="_blank">🛒 eBay</a>
            </div>

            <div class="footer-column newsletter-section">
                <h2>📧 Iscriviti alla Newsletter</h2>
                <p>Ricevi offerte esclusive e novità</p>
                <form class="newsletter-form" onsubmit="event.preventDefault(); alert('Grazie per esserti iscritto!');">
                    <input type="email" class="newsletter-input" placeholder="La tua email" required>
                    <button type="submit" class="newsletter-btn">Iscriviti</button>
                </form>
            </div>

            <div class="footer-column chat-column">
                <h2>💬 Chat con noi</h2>
                <div class="footer-chat" id="chatWidget">
                    <div class="chat-messages" id="chatMessages">
                        <div class="chat-message bot">Ciao! 👋 Come posso aiutarti?</div>
                    </div>
                    <div class="chat-input-area">
                        <input type="text" class="chat-input" id="chatInput" placeholder="Scrivi..." onkeypress="if(event.key==='Enter') sendMessage()">
                        <button class="chat-send" onclick="sendMessage()">Invia</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="footer-bottom">
            <p>&copy; <?php echo date('Y'); ?> <?php echo $sito['nome']; ?> - Tutti i diritti riservati | P.IVA <?php echo $sito['piva']; ?></p>
            <p>🌿 Benessere naturale dal 1970 🌿</p>
            <p style="font-size: 11px; margin-top: 10px;">
                <a href="#" style="color: #f4d03f; text-decoration: none;">ODR - Risoluzione Online Controversie</a> | 
                <a href="https://ec.europa.eu/consumers/odr" target="_blank" style="color: #f4d03f;"> Piattaforma ODR UE</a>
            </p>
        </div>
    </footer>

    <script>
        // Slider
        let currentSlide = 0;
        const slides = document.querySelectorAll('.slide');
        const slider = document.getElementById('slider');

        function moveSlide(direction) {
            currentSlide += direction;
            if (currentSlide < 0) currentSlide = slides.length - 1;
            if (currentSlide >= slides.length) currentSlide = 0;
            slider.style.transform = `translateX(-${currentSlide * 100}%)`;
        }
        setInterval(() => moveSlide(1), 5000);

        // Carrello
        function addToCart(productId) {
            const count = document.getElementById('cartCount');
            count.textContent = parseInt(count.textContent) + 1;
            alert('Prodotto aggiunto al carrello!');
        }

        // Video
        const videos = <?php echo json_encode(array_map(function($v) { return ['src' => $v['url'], 'title' => $v['titolo']]; }, $video)); ?>;
        let currentVideoIndex = 0;

        function loadVideo(index) {
            currentVideoIndex = index;
            document.getElementById('videoSource').src = videos[index].src;
            document.getElementById('videoPlayer').load();
            document.querySelectorAll('.playlist-item').forEach((item, i) => {
                item.classList.toggle('active', i === index);
            });
        }

        function playPauseVideo() {
            const player = document.getElementById('videoPlayer');
            if (player.paused) player.play();
            else player.pause();
        }

        function nextVideo() {
            currentVideoIndex = (currentVideoIndex + 1) % videos.length;
            loadVideo(currentVideoIndex);
        }

        function previousVideo() {
            currentVideoIndex = (currentVideoIndex - 1 + videos.length) % videos.length;
            loadVideo(currentVideoIndex);
        }

        // Chat
        function sendMessage() {
            const input = document.getElementById('chatInput');
            const message = input.value.trim();
            if (message) {
                const container = document.getElementById('chatMessages');
                const userMsg = document.createElement('div');
                userMsg.className = 'chat-message user';
                userMsg.textContent = message;
                container.appendChild(userMsg);
                input.value = '';
                setTimeout(() => {
                    const botMsg = document.createElement('div');
                    botMsg.className = 'chat-message bot';
                    botMsg.textContent = 'Grazie per il messaggio! Ti risponderemo presto 😊';
                    container.appendChild(botMsg);
                    container.scrollTop = container.scrollHeight;
                }, 800);
            }
        }
    </script>
</body>
</html>
