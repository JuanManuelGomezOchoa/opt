<?php
// Fragmento reutilizable: wordmark de la marca (SVG + tipografía Poppins).
// Se incluye en navbar, footer, login y sidenav; el color lo hereda de
// currentColor según el contexto (rojo de marca, blanco en panel, etc.).
?>
<span class="brand-logo">
    <svg class="brand-icon" viewBox="0 0 24 24" width="26" height="26" fill="none" aria-hidden="true" focusable="false">
        <path d="M6 7V5.5a1.5 1.5 0 0 1 1.5-1.5h9A1.5 1.5 0 0 1 18 5.5V7h3a1 1 0 0 1 1 1v11a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V8a1 1 0 0 1 1-1h3Zm2 0h8V6H8v1ZM4 9v10h16V9H4Zm5 2a3 3 0 0 0 6 0h2v.1A4 4 0 0 1 12 15a4 4 0 0 1-5-3.9V11h2Z" fill="currentColor"/>
    </svg>
    <span class="brand-wordmark">e&#8209;commerce</span>
</span>