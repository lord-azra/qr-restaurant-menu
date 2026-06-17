<?php
declare(strict_types=1);

session_start();

$dataDir = __DIR__ . '/data';

function load_json(string $file, array $default = []): array {
    if (!file_exists($file)) return $default;
    $raw = file_get_contents($file);
    $data = json_decode($raw, true);
    return is_array($data) ? $data : $default;
}

function save_json(string $file, array $data): void {
    file_put_contents($file, json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
}

function h(string $s): string {
    return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
}

$adminPass = "111111";

if (!isset($_SESSION['login'])) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (($_POST['password'] ?? '') === $adminPass) {
            $_SESSION['login'] = true;
            header("Location: admin.php");
            exit;
        }
        $error = "Hatalı şifre";
    }
    ?>
    <form method="POST" style="max-width:300px;margin:100px auto;">
        <h3>Admin Giriş</h3>
        <input type="password" name="password" placeholder="Şifre">
        <button>Giriş</button>
        <p style="color:red;"><?php echo $error ?? ''; ?></p>
    </form>
    <?php
    exit;
}

$categoriesFile = $dataDir . '/categories.json';
$menuFile = $dataDir . '/menu.json';

$categories = load_json($categoriesFile, []);
$menu = load_json($menuFile, []);

$action = $_GET['action'] ?? '';

/* KATEGORİ EKLE */
if ($action === 'add_category' && $_SERVER['REQUEST_METHOD'] === 'POST') {

    $categories[] = [
        'id' => uniqid(),
        'name' => $_POST['name'] ?? '',
        'image' => $_POST['image'] ?? ''
    ];

    save_json($categoriesFile, $categories);
    header("Location: admin.php");
    exit;
}

/* ÜRÜN EKLE */
if ($action === 'add_menu' && $_SERVER['REQUEST_METHOD'] === 'POST') {

    $menu[] = [
        'id' => uniqid(),
        'category_id' => $_POST['category_id'] ?? '',
        'name' => $_POST['name'] ?? '',
        'desc' => $_POST['desc'] ?? '',
        'price' => $_POST['price'] ?? '',
        'image' => $_POST['image'] ?? ''
    ];

    save_json($menuFile, $menu);
    header("Location: admin.php");
    exit;
}

/* SİL */
if ($action === 'delete_category') {
    $id = $_GET['id'] ?? '';
    $categories = array_values(array_filter($categories, fn($c) => ($c['id'] ?? '') !== $id));
    save_json($categoriesFile, $categories);
    header("Location: admin.php");
    exit;
}

if ($action === 'delete_menu') {
    $id = $_GET['id'] ?? '';
    $menu = array_values(array_filter($menu, fn($m) => ($m['id'] ?? '') !== $id));
    save_json($menuFile, $menu);
    header("Location: admin.php");
    exit;
}

?>
<!doctype html>
<html lang="tr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="assets/css/admin.css">
</head>
<body>

<div class="container">

<h2>Admin Panel</h2>

<h3>Kategori Ekle</h3>
<form method="POST" action="?action=add_category">
    <input name="name" placeholder="Kategori adı" required>
    <input name="image" placeholder="Resim URL">
    <button>Ekle</button>
</form>

<h3>Ürün Ekle</h3>
<form method="POST" action="?action=add_menu">
    <select name="category_id" required>
        <?php foreach ($categories as $c): ?>
            <option value="<?php echo h($c['id']); ?>">
                <?php echo h($c['name']); ?>
            </option>
        <?php endforeach; ?>
    </select>

    <input name="name" placeholder="Ürün adı" required>
    <input name="desc" placeholder="Açıklama">
    <input name="price" placeholder="Fiyat">
    <input name="image" placeholder="Resim URL">

    <button>Ekle</button>
</form>

<hr>

<h3>Kategoriler</h3>
<table>
<tr><th>Ad</th><th>İşlem</th></tr>
<?php foreach ($categories as $c): ?>
<tr>
<td><?php echo h($c['name']); ?></td>
<td>
    <a href="?action=delete_category&id=<?php echo h($c['id']); ?>">Sil</a>
</td>
</tr>
<?php endforeach; ?>
</table>

<h3>Ürünler</h3>
<table>
<tr><th>Ad</th><th>Fiyat</th><th>İşlem</th></tr>
<?php foreach ($menu as $m): ?>
<tr>
<td><?php echo h($m['name']); ?></td>
<td><?php echo h($m['price']); ?> ₺</td>
<td>
    <a href="?action=delete_menu&id=<?php echo h($m['id']); ?>">Sil</a>
</td>
</tr>
<?php endforeach; ?>
</table>

</div>

</body>
</html>
