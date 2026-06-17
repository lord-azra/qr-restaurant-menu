<?php
declare(strict_types=1);

$dataDir = __DIR__ . '/data';

function load_json(string $file, array $default = []): array {
    if (!file_exists($file)) return $default;
    $raw = file_get_contents($file);
    $data = json_decode($raw, true);
    return is_array($data) ? $data : $default;
}

$categories = load_json($dataDir . '/categories.json', []);
$menu = load_json($dataDir . '/menu.json', []);

$id = $_GET['id'] ?? '';

$category = null;
foreach ($categories as $c) {
    if (($c['id'] ?? '') === $id) {
        $category = $c;
        break;
    }
}

if (!$category) {
    header("Location: index.php");
    exit;
}

function h(string $s): string {
    return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
}
?>

<!doctype html>
<html lang="tr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo h($category['name']); ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<header class="header">
    <a href="index.php">⬅ Geri</a>
    <span><?php echo h($category['name']); ?></span>
</header>

<main class="container">

    <div class="menu-list">

        <?php foreach ($menu as $item): ?>
            <?php if (($item['category_id'] ?? '') !== $id) continue; ?>

            <div class="menu-item">

                <div class="menu-img"
                     style="background-image:url('<?php echo h($item['image'] ?? ''); ?>')">
                </div>

                <div class="menu-info">
                    <div class="menu-name">
                        <?php echo h($item['name']); ?>
                    </div>

                    <div class="menu-desc">
                        <?php echo h($item['desc'] ?? ''); ?>
                    </div>
                </div>

                <div class="menu-price">
                    <?php echo h(($item['price'] ?? '') . ' ₺'); ?>
                </div>

            </div>

        <?php endforeach; ?>

    </div>

</main>

</body>
</html>
