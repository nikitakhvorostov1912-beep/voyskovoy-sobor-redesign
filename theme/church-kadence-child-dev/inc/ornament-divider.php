<?php
/**
 * Ornament Divider — крест с виноградной лозой
 * Используется как разделитель между секциями
 */
?>
<div class="cc-divider" role="separator" aria-hidden="true">
    <svg viewBox="0 0 280 32" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid meet">
        <g stroke="currentColor" fill="none" stroke-width="1" stroke-linecap="round">
            <!-- Левая лоза -->
            <path d="M10 16 Q40 16 70 12 Q85 10 100 14"/>
            <circle cx="40" cy="14" r="1.2" fill="currentColor"/>
            <circle cx="65" cy="11" r="1.2" fill="currentColor"/>
            <path d="M55 14 Q58 11 60 14" stroke-width="0.7"/>
            <!-- Правая лоза -->
            <path d="M180 14 Q195 10 210 12 Q240 16 270 16"/>
            <circle cx="240" cy="14" r="1.2" fill="currentColor"/>
            <circle cx="215" cy="11" r="1.2" fill="currentColor"/>
            <path d="M220 14 Q223 11 225 14" stroke-width="0.7"/>
            <!-- Центральный крест -->
            <g transform="translate(140 16)" stroke-width="1.4">
                <line x1="0" y1="-12" x2="0" y2="12"/>
                <line x1="-9" y1="-4" x2="9" y2="-4"/>
                <line x1="-7" y1="-9" x2="7" y2="-9"/>
                <line x1="-6" y1="6" x2="6" y2="6"/>
            </g>
        </g>
    </svg>
</div>
