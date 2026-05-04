<?php
session_start();

if (!isset($_SESSION['filmes'])) {
    $_SESSION['filmes'] = [
        ['id' => 1, 'titulo' => 'Interestelar', 'diretor' => 'Christopher Nolan', 'ano' => 2014, 'genero' => 'Ficção Científica', 'nota' => 10],
        ['id' => 2, 'titulo' => 'Coringa',       'diretor' => 'Todd Phillips',     'ano' => 2019, 'genero' => 'Drama',             'nota' => 9],
        ['id' => 3, 'titulo' => 'Parasita',      'diretor' => 'Bong Joon-ho',      'ano' => 2019, 'genero' => 'Suspense',          'nota' => 9],
    ];
    $_SESSION['next_id'] = 4;
}

$filmes  = &$_SESSION['filmes'];
$next_id = &$_SESSION['next_id'];

if ($_POST['acao'] ?? '' === 'criar') {
    $filmes[] = ['id' => $next_id++, 'titulo' => $_POST['titulo'], 'diretor' => $_POST['diretor'], 'ano' => $_POST['ano'], 'genero' => $_POST['genero'], 'nota' => $_POST['nota']];
}

if ($_POST['acao'] ?? '' === 'atualizar') {
    foreach ($filmes as &$f) {
        if ($f['id'] == $_POST['id']) {
            $f = ['id' => (int)$_POST['id'], 'titulo' => $_POST['titulo'], 'diretor' => $_POST['diretor'], 'ano' => $_POST['ano'], 'genero' => $_POST['genero'], 'nota' => $_POST['nota']];
            break;
        }
    }
    unset($f);
}

if ($_POST['acao'] ?? '' === 'excluir') {
    $filmes = array_values(array_filter($filmes, fn($f) => $f['id'] != $_POST['id']));
}

$editando = null;
if (isset($_GET['editar'])) {
    foreach ($filmes as $f) {
        if ($f['id'] == $_GET['editar']) { $editando = $f; break; }
    }
}

$generos = ['Ação','Animação','Aventura','Comédia','Drama','Fantasia','Ficção Científica','Horror','Romance','Suspense','Outros'];
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Cadastro de Filmes</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body class="bg-light">

<nav class="navbar navbar-dark bg-dark px-4">
  <span class="navbar-brand fw-bold">Cadastro de Filmes</span>
  <span class="text-secondary small"><?= count($filmes) ?> filme(s)</span>
</nav>

<div class="container py-4" style="max-width:900px">

  <div class="card shadow-sm mb-4">
    <div class="card-header"><?= $editando ? 'Editar Filme' : 'Novo Filme' ?></div>
    <div class="card-body">
      <form method="POST">
        <input type="hidden" name="acao" value="<?= $editando ? 'atualizar' : 'criar' ?>" />
        <?php if ($editando): ?>
          <input type="hidden" name="id" value="<?= $editando['id'] ?>" />
        <?php endif; ?>

        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label">Título</label>
            <input type="text" name="titulo" class="form-control" value="<?= htmlspecialchars($editando['titulo'] ?? '') ?>" />
          </div>
          <div class="col-md-6">
            <label class="form-label">Diretor</label>
            <input type="text" name="diretor" class="form-control" value="<?= htmlspecialchars($editando['diretor'] ?? '') ?>" />
          </div>
          <div class="col-md-4">
            <label class="form-label">Ano</label>
            <input type="number" name="ano" class="form-control" value="<?= htmlspecialchars($editando['ano'] ?? '') ?>" />
          </div>
          <div class="col-md-4">
            <label class="form-label">Gênero</label>
            <select name="genero" class="form-select">
              <option value="">Selecione...</option>
              <?php foreach ($generos as $g): ?>
                <option <?= ($editando['genero'] ?? '') === $g ? 'selected' : '' ?>><?= $g ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="col-md-4">
            <label class="form-label">Nota (1–10)</label>
            <input type="number" name="nota" class="form-control" value="<?= htmlspecialchars($editando['nota'] ?? '') ?>" />
          </div>
        </div>

        <div class="mt-3 d-flex gap-2">
          <button type="submit" class="btn btn-dark"><?= $editando ? 'Salvar' : 'Adicionar' ?></button>
          <?php if ($editando): ?>
            <a href="filmes.php" class="btn btn-outline-secondary">Cancelar</a>
          <?php endif; ?>
        </div>
      </form>
    </div>
  </div>

  <div class="card shadow-sm">
    <div class="card-header">Filmes Cadastrados</div>
    <div class="card-body p-0">
      <?php if (empty($filmes)): ?>
        <p class="text-center text-muted py-4">Nenhum filme cadastrado.</p>
      <?php else: ?>
      <table class="table table-hover align-middle mb-0">
        <thead class="table-light">
          <tr>
            <th>Título</th>
            <th>Diretor</th>
            <th>Ano</th>
            <th>Gênero</th>
            <th>Nota</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($filmes as $f): ?>
          <tr>
            <td class="fw-semibold"><?= htmlspecialchars($f['titulo']) ?></td>
            <td><?= htmlspecialchars($f['diretor'] ?: '—') ?></td>
            <td><?= $f['ano'] ?: '—' ?></td>
            <td><?= htmlspecialchars($f['genero'] ?: '—') ?></td>
            <td><?= $f['nota'] ? '⭐ ' . $f['nota'] : '—' ?></td>
            <td class="text-end">
              <a href="filmes.php?editar=<?= $f['id'] ?>" class="btn btn-sm btn-outline-primary">Editar</a>
              <form method="POST" class="d-inline" onsubmit="return confirm('Excluir?')">
                <input type="hidden" name="acao" value="excluir" />
                <input type="hidden" name="id" value="<?= $f['id'] ?>" />
                <button type="submit" class="btn btn-sm btn-outline-danger">Excluir</button>
              </form>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
      <?php endif; ?>
    </div>
  </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
