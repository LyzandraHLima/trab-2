<?php
session_start();

if (!isset($_SESSION['series_v2'])) {
    unset($_SESSION['filmes'], $_SESSION['series']);
    $_SESSION['series_v2'] = [
        ['id' =>  1, 'titulo' => 'Game of Thrones',       'genero' => 'Fantasia', 'nota' => 9],
        ['id' =>  2, 'titulo' => 'Breaking Bad',           'genero' => 'Drama',    'nota' => 10],
        ['id' =>  3, 'titulo' => 'Classroom of the Elite', 'genero' => 'Anime',    'nota' => 8],
        ['id' =>  4, 'titulo' => 'The Sopranos',           'genero' => 'Drama',    'nota' => 10],
        ['id' =>  5, 'titulo' => 'Peaky Blinders',         'genero' => 'Drama',    'nota' => 9],
        ['id' =>  6, 'titulo' => 'Hora de Aventura',       'genero' => 'Animação', 'nota' => 9],
        ['id' =>  7, 'titulo' => 'Elite',                  'genero' => 'Drama',    'nota' => 8],
        ['id' =>  8, 'titulo' => 'Baby',                   'genero' => 'Drama',    'nota' => 7],
        ['id' =>  9, 'titulo' => 'You',                    'genero' => 'Suspense', 'nota' => 8],
        ['id' => 10, 'titulo' => 'The Boys',               'genero' => 'Ação',    'nota' => 9],
<<<<<<< alteracao-lyz
        ['id' => 11, 'titulo' => 'Vikings',                'genero' => 'Ação',    'nota' => 7],
        ['id' => 12, 'titulo' => 'O poderoso chefão', 'genero' => 'Drama/Crime',    'nota' => 10],
        ['id' => 13, 'titulo' => 'O poderoso chefão 2', 'genero' => 'Drama/Crime',    'nota' => 11],
=======
        ['id' => 11, 'titulo' => 'Vikings',                'genero' => 'Ação',    'nota' => 8],
        //
        ['id' => 12, 'titulo' => 'Naruto',                'genero' => 'SHOUNEN',    'nota' => 10],
        ['id' => 13, 'titulo' => 'Motoqueiro Fantasma',                'genero' => 'Ação',    'nota' => 10],
        ['id' => 14, 'titulo' => 'Hereditario',                'genero' => 'Terror',    'nota' => 10],
        ['id' => 15, 'titulo' => 'IT',                'genero' => 'Terror',    'nota' => 10],
        ['id' => 16, 'titulo' => 'O silencio dos Inocentes',                'genero' => 'Suspense',    'nota' => 10],
        ['id' => 17, 'titulo' => 'Crepusculo',                'genero' => 'Romance',    'nota' => 10],
        ['id' => 18, 'titulo' => 'InterEstelar',                'genero' => 'Ficção Cientifica',    'nota' => 10],
        ['id' => 19, 'titulo' => 'A Chegada',                'genero' => 'Ficção Cientifica',    'nota' => 10],
        ['id' => 20, 'titulo' => 'A Espera de Um Milagre',                'genero' => 'Drama',    'nota' => 10],
>>>>>>> vinicius
    ];
    $_SESSION['next_id'] = 14;
}

$series  = &$_SESSION['series_v2'];
$next_id = &$_SESSION['next_id'];

if (($_POST['acao'] ?? '') === 'criar') {
    $series[] = ['id' => $next_id++, 'titulo' => $_POST['titulo'], 'genero' => $_POST['genero'], 'nota' => $_POST['nota']];
}

if (($_POST['acao'] ?? '') === 'atualizar') {
    foreach ($series as &$s) {
        if ($s['id'] == $_POST['id']) {
            $s = ['id' => (int)$_POST['id'], 'titulo' => $_POST['titulo'], 'genero' => $_POST['genero'], 'nota' => $_POST['nota']];
            break;
        }
    }
    unset($s);
}

if (($_POST['acao'] ?? '') === 'excluir') {
    $series = array_values(array_filter($series, fn($s) => $s['id'] != $_POST['id']));
}

$editando = null;
if (isset($_GET['editar'])) {
    foreach ($series as $s) {
        if ($s['id'] == $_GET['editar']) { $editando = $s; break; }
    }
}

$generos = ['Ação','Animação','Anime','Aventura','Comédia','Drama','Fantasia','Ficção Científica','Horror','Romance','Suspense','Outros'];
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Cadastro de Séries</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body class="bg-light">

<nav class="navbar navbar-dark bg-dark px-4">
  <span class="navbar-brand fw-bold">Cadastro de Séries</span>
  <span class="text-secondary small"><?= count($series) ?> série(s)</span>
</nav>

<div class="container py-4" style="max-width:900px">

  <div class="card shadow-sm mb-4">
    <div class="card-header"><?= $editando ? 'Editar Série' : 'Nova Série' ?></div>
    <div class="card-body">
      <form method="POST">
        <input type="hidden" name="acao" value="<?= $editando ? 'atualizar' : 'criar' ?>" />
        <?php if ($editando): ?>
          <input type="hidden" name="id" value="<?= $editando['id'] ?>" />
        <?php endif; ?>

        <div class="row g-3">
          <div class="col-md-4">
            <label class="form-label">Título</label>
            <input type="text" name="titulo" class="form-control" value="<?= htmlspecialchars($editando['titulo'] ?? '') ?>" />
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
    <div class="card-header">Séries Cadastradas</div>
    <div class="card-body p-0">
      <?php if (empty($series)): ?>
        <p class="text-center text-muted py-4">Nenhuma série cadastrada.</p>
      <?php else: ?>
      <table class="table table-hover align-middle mb-0">
        <thead class="table-light">
          <tr>
            <th>Título</th>
            <th>Gênero</th>
            <th>Nota</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($series as $s): ?>
          <tr>
            <td class="fw-semibold"><?= htmlspecialchars($s['titulo']) ?></td>
            <td><?= htmlspecialchars($s['genero'] ?: '—') ?></td>
            <td><?= $s['nota'] ? '⭐ ' . $s['nota'] : '—' ?></td>
            <td class="text-end">
              <a href="filmes.php?editar=<?= $s['id'] ?>" class="btn btn-sm btn-outline-primary">Editar</a>
              <form method="POST" class="d-inline" onsubmit="return confirm('Excluir?')">
                <input type="hidden" name="acao" value="excluir" />
                <input type="hidden" name="id" value="<?= $s['id'] ?>" />
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
