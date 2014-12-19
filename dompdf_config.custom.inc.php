<?php 
//define("DOMPDF_TEMP_DIR", "/tmp");
//define("DOMPDF_CHROOT", DOMPDF_DIR);
//define("DOMPDF_FONT_DIR", DOMPDF_DIR."/lib/fonts/");
//define("DOMPDF_FONT_CACHE", DOMPDF_DIR."/lib/fonts/");
define("DOMPDF_UNICODE_ENABLED", true);
//define("DOMPDF_PDF_BACKEND", "PDFLib");
define("DOMPDF_DEFAULT_MEDIA_TYPE", "print");
define("DOMPDF_DEFAULT_PAPER_SIZE", "A4");
//define("DOMPDF_DEFAULT_FONT", "serif");
define("DOMPDF_DPI", 100);
define("DOMPDF_SVG_SUPERSAMPLE", 3);
//define("DOMPDF_ENABLE_PHP", true);
define("DOMPDF_ENABLE_REMOTE", true);
//define("DOMPDF_ENABLE_CSS_FLOAT", true);
//define("DOMPDF_ENABLE_JAVASCRIPT", false);
//define("DEBUGPNG", true);
//define("DEBUGKEEPTEMP", true);
//define("DEBUGCSS", true);
//define("DEBUG_LAYOUT", true);
//define("DEBUG_LAYOUT_LINES", true);
//define("DEBUG_LAYOUT_BLOCKS", true);
//define("DEBUG_LAYOUT_INLINE", true);
define("DOMPDF_FONT_HEIGHT_RATIO", 1.0);
//define("DEBUG_LAYOUT_PADDINGBOX", true);
//define("DOMPDF_LOG_OUTPUT_FILE", DOMPDF_FONT_DIR."log.htm");
//define("DOMPDF_ENABLE_HTML5PARSER", true);
define("DOMPDF_ENABLE_FONTSUBSETTING", false);
// DOMPDF authentication
//define("DOMPDF_ADMIN_USERNAME", "user");
//define("DOMPDF_ADMIN_PASSWORD", "password");

// Dirty quick fix for constant graph size
define("DOMPDF_QUICKFIX_GRAPH_PROPORTIONS", 82/60);
define("DOMPDF_QUICKFIX_GRAPH_BASE_DPI", 50);
define("DOMPDF_CM_TO_INCH", 2.540);
