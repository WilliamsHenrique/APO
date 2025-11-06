<?php
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("Acesso inválido. Por favor, preencha o formulário.");
}
function limpar_entrada($dado) {
    return htmlspecialchars(trim($dado), ENT_QUOTES, 'UTF-8');
}

$nome = limpar_entrada($_POST['nome'] ?? 'Candidato');
$objetivo = limpar_entrada($_POST['objetivo'] ?? 'Objetivo Profissional');
$email = limpar_entrada($_POST['email'] ?? 'nao_informado@exemplo.com');
$telefone = limpar_entrada($_POST['telefone'] ?? 'N/A');
$endereco = limpar_entrada($_POST['endereco'] ?? 'N/A');
$idade = limpar_entrada($_POST['idade'] ?? 'N/A');
$caminho_foto = '';
if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
    $temp_name = $_FILES['foto']['tmp_name'];
    $file_extension = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
    $file_name = uniqid('cv_') . '.' . $file_extension;
    $upload_dir = 'uploads/'; 
    $caminho_foto = $upload_dir . $file_name;
    if (move_uploaded_file($temp_name, $caminho_foto)) {
    } else {
        $caminho_foto = '';
    }
}
function gerar_lista_html($campos_map, $template_item) {
    $html = '';
    $total_itens = count($_POST[$campos_map[0]['name']] ?? []); 

    if ($total_itens > 0) {
        for ($i = 0; $i < $total_itens; $i++) {
            $valores = [];
            $item_valido = false;
            foreach ($campos_map as $campo) {
                $valor = limpar_entrada($_POST[$campo['name']][$i] ?? '');
                $valores[$campo['placeholder']] = $valor;
                if ($campo['principal'] && !empty($valor)) {
                    $item_valido = true;
                }
            }
            if ($item_valido) {
                $item_html = $template_item;
                foreach ($valores as $placeholder => $valor) {
                    if (strpos($placeholder, 'descricao') !== false) {
                        $valor = nl2br($valor);
                    }
                    $item_html = str_replace("{{$placeholder}}", $valor, $item_html);
                }
                $html .= $item_html;
            }
        }
    }
    return $html;
}
$map_experiencia = [
    ['name' => 'cargo', 'placeholder' => 'cargo', 'principal' => true],
    ['name' => 'empresa', 'placeholder' => 'empresa', 'principal' => false],
    ['name' => 'descricao', 'placeholder' => 'descricao', 'principal' => false],
];

$template_experiencia = '
    <div class="cv-job">
        <h4 class="job-title">{{cargo}} na {{empresa}}</h4>
        <p class="job-description">{{descricao}}</p>
    </div>
';

$experiencia_html = gerar_lista_html($map_experiencia, $template_experiencia);
$map_formacao = [
    ['name' => 'curso', 'placeholder' => 'curso', 'principal' => true],
    ['name' => 'instituicao', 'placeholder' => 'instituicao', 'principal' => false],
    ['name' => 'periodo', 'placeholder' => 'periodo', 'principal' => false],
];

$template_formacao = '
    <div class="cv-formacao-item">
        <h4 class="formacao-curso">{{curso}}</h4>
        <p class="formacao-detalhe">{{instituicao}} ({{periodo}})</p>
    </div>
';

$formacao_html = gerar_lista_html($map_formacao, $template_formacao);
$display_foto = !empty($caminho_foto) ? $caminho_foto : 'data:image/gif;base64,R0lGODlhAQABAAD/ACwAAAAAAQABAAACADs='; // Imagem vazia
$foto_class = empty($caminho_foto) ? 'hidden' : '';
$idade_display = !empty($idade) ? "Idade: {$idade}" : '';
$template_cv_html = <<<HTML
<!DOCTYPE html>
<html>
<head>
    <title>{$nome} - Currículo</title>
    <link rel="stylesheet" href="css/template-classico.css">
</head>
<body>
    <div id="cv-template">
        
        <header id="cv-header">
            <img id="cv-foto" src="{$display_foto}" alt="Foto do Candidato" class="{$foto_class}">
            <div>
                <h1 id="cv-nome">{$nome}</h1>
                <h2 id="cv-objetivo">{$objetivo}</h2>
            </div>
        </header>
        
        <div id="cv-contato">
            <span id="cv-email" class="cv-detail">{$email}</span> 
            <span id="cv-telefone" class="cv-detail">{$telefone}</span> 
            <span id="cv-endereco" class="cv-detail">{$endereco}</span> 
            <span id="cv-idade" class="cv-detail">{$idade_display}</span>
        </div>

        <section class="cv-section">
            <h3 class="cv-section-title">Formação Acadêmica</h3>
            <div id="cv-formacao">
                {$formacao_html}
            </div>
        </section>

        <section class="cv-section">
            <h3 class="cv-section-title">Experiência Profissional</h3>
            <div id="cv-experiencia">
                {$experiencia_html}
            </div>
        </section>
    </div>
    <button onclick="window.print()" class="btn-print">Imprimir/Baixar PDF</button>
</body>
</html>
HTML;
echo $template_cv_html;
?>