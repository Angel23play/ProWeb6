<?php
require_once __DIR__ . '/../vendor/autoload.php';

// No uses "use TCPDF;" porque TCPDF no tiene namespace

class Pdf
{
    private $tcpdf;

    public function __construct()
    {
        // Inicializar TCPDF
        $this->tcpdf = new \TCPDF();

        // Configuración del documento
        $this->tcpdf->SetCreator(PDF_CREATOR);
        $this->tcpdf->SetAuthor('Sistema de Personajes');
        $this->tcpdf->SetTitle('Ficha de Personaje');
        $this->tcpdf->SetMargins(15, 20, 15);
        $this->tcpdf->AddPage();
    }

    public function CrearPDF($nombre, $color, $tipo, $nivel, $foto)
    {
        $templatePath = __DIR__ . '/template.html';

        if (!file_exists($templatePath)) {
            die("El archivo template.html no existe");
        }

        // Reemplazo de imagen si es WebP o vacía
        if ($this->esWebp($foto) || empty($foto)) {
            $foto = 'https://via.placeholder.com/300x200.png?text=Sin+imagen';
        }

        // Cargar y reemplazar los placeholders del HTML
        $html = file_get_contents($templatePath);
        $html = str_replace(
            ['{{nombre}}', '{{color}}', '{{tipo}}', '{{nivel}}', '{{foto}}'],
            [
                htmlspecialchars($nombre),
                htmlspecialchars($color),
                htmlspecialchars($tipo),
                htmlspecialchars($nivel),
                htmlspecialchars($foto)
            ],
            $html
        );

        // Generar PDF con HTML
        $this->tcpdf->writeHTML($html, true, false, true, false, '');

        // Mostrar PDF en navegador
        $this->tcpdf->Output($nombre . '_ficha.pdf', 'I');

        exit;
    }

    private function esWebp($url)
    {
        return preg_match('/\.webp(\?.*)?$/i', $url);
    }
}
