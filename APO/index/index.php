<?php 
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Gerador de Currículo PHP</title>
    <link rel="stylesheet" href="css/index.css"> 
    <link rel="stylesheet" href="css/template-classico.css" id="cv-template-style">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <style>
        .template-btn.active { background-color: #007bff; color: white; }
    </style>
</head>
<body>

    <div class="container">
        <form action="gerar_cv.php" method="POST" enctype="multipart/form-data">
            
            <section id="input-form">
                
                <h2>1. 📝 Dados Pessoais e Contato</h2>
                <div class="form-group"><label for="nome">Nome Completo</label><input type="text" id="nome" name="nome" class="form-control"></div>
                <div class="form-group"><label for="cargo-objetivo">Cargo/Objetivo</label><input type="text" id="cargo-objetivo" name="objetivo" class="form-control"></div>

                <div class="form-grid">
                    <div class="form-group"><label for="email">E-mail</label><input type="email" id="email" name="email" class="form-control"></div>
                    <div class="form-group"><label for="telefone">Telefone</label><input type="tel" id="telefone" name="telefone" class="form-control"></div>
                    <div class="form-group"><label for="endereco">Endereço</label><input type="text" id="endereco" name="endereco" class="form-control"></div>
                    <div class="form-group"><label for="idade">Idade</label><input type="number" id="idade" name="idade" class="form-control"></div>
                </div>

                <div class="form-group">
                    <label for="foto">Sua Foto</label>
                    <input type="file" id="foto-input" name="foto" accept="image/*">
                </div>

                <div id="formacao-container" class="dynamic-section">
                    <h2>2. 🎓 Formação Acadêmica</h2>
                    <div id="formacao-lista">
                        </div>
                    <button type="button" id="add-formacao" data-target="formacao" class="btn-add">Adicionar Formação</button>
                </div>

                <div id="experiencia-container" class="dynamic-section">
                    <h2>3. 💼 Experiência Profissional</h2>
                    <div id="experiencia-lista">
                        </div>
                    <button type="button" id="add-experiencia" data-target="experiencia" class="btn-add">Adicionar Experiência</button>
                </div>

            </section>
            
            <button type="submit" class="btn-primary" style="margin-top: 20px;">Gerar Currículo (Via PHP)</button>
        </form>
    </div>

<script>
$(document).ready(function() {
        const experienceHtmlBase = `
        <div class="experiencia-item">
            <input type="text" name="cargo[]" class="form-control" placeholder="Cargo">
            <input type="text" name="empresa[]" class="form-control" placeholder="Empresa">
            <textarea name="descricao[]" class="form-control" placeholder="Principais Responsabilidades"></textarea>
            <button type="button" class="remove-item">Remover</button>
        </div>
    `;
    const formacaoHtmlBase = `
        <div class="formacao-item">
            <input type="text" name="curso[]" class="form-control" placeholder="Curso/Graduação">
            <input type="text" name="instituicao[]" class="form-control" placeholder="Instituição">
            <input type="text" name="periodo[]" class="form-control" placeholder="Ano de Início - Ano de Fim">
            <button type="button" class="remove-item">Remover</button>
        </div>
    `;
    $('#add-experiencia').on('click', function() {
        $('#experiencia-lista').append(experienceHtmlBase);
    });

    $('#add-formacao').on('click', function() {
        $('#formacao-lista').append(formacaoHtmlBase);
    });
    $('#experiencia-lista, #formacao-lista').on('click', '.remove-item', function() {
        $(this).closest('.experiencia-item, .formacao-item').remove();
    });
});
</script>
</body>
</html>