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
        <button type="submit">Giriş</button>
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

?>
<!doctype html>
<html lang="tr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Panel</title>
</head>
<body style="font-family:Arial;padding:20px;">

<h2>Admin Panel</h2>

<h3>Kategori Ekle</h3>
<form method="POST" action="?action=add_category">
    <input name="name" placeholder="Kategori adı">
    <input name="image" placeholder="Resim URL">
    <button>Kaydet</button>
</form>

<h3>Ürün Ekle</h3>
<form method="POST" action="?action=add_menu">
    <select name="category_id">
        <?php foreach ($categories as $c): ?>
            <option value="<?php echo $c['id']; ?>">
                <?php echo $c['name']; ?>
            </option>
        <?php endforeach; ?>
    </select>

    <input name="name" placeholder="Ürün adı">
    <input name="desc" placeholder="Açıklama">
    <input name="price" placeholder="Fiyat">
    <input name="image" placeholder="Resim URL">

    <button>Kaydet</button>
</form>

<hr>

<h3>Kategoriler</h3>
<pre><?php print_r($categories); ?></pre>

<h3>Ürünler</h3>
<pre><?php print_r($menu); ?></pre>

</body>
</html>
