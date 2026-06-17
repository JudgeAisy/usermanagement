<?php
require_once 'autoload.php';

$fakeNames = ['Taha', 'Ahmet', 'Mehmet', 'Can', 'Murat', 'Selin', 'Ebru', 'Gizem'];
$fakeSurnames = ['Cahit', 'Yılmaz', 'Kaya', 'Demir', 'Şahin', 'Çelik', 'Yıldız'];

$studentsList = [];

for ($i = 1; $i <= 10; $i++) {
    $randomName = $fakeNames[array_rand($fakeNames)] . ' ' . $fakeSurnames[array_rand($fakeSurnames)];
    $randomEmail = strtolower($fakeNames[array_rand($fakeNames)]) . $i . "@example.com";
    
    $deletedAt = ($i == 3 || $i == 7) ? date('Y-m-d H:i:s') : null;
    
    if ($i % 2 == 0) {
        $studentsList[$i] = new \App\Models\Admin($randomName, $randomEmail, 'pass123', $deletedAt);
    } else {
        $studentsList[$i] = new \App\Models\RegularUser($randomName, $randomEmail, 'pass123', $deletedAt);
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>SoftDelete & Faker Uygulaması</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="card shadow-sm">
        <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Öğrenci / Kullanıcı Listesi (SoftDelete & Faker)</h4>
            <span class="badge bg-primary">Faker Seeder Active</span>
        </div>
        <div class="card-body">
            <table class="table table-striped table-hover mt-3">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>İsim Surname</th>
                        <th>E-posta</th>
                        <th>Rol</th>
                        <th>Durum</th>
                        <th>İşlemler</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($studentsList as $id => $student): ?>
                        <tr>
                            <td><?= $id; ?></td>
                            <td><?= $student->getName(); ?></td>
                            <td><?= $student->getEmail(); ?></td>
                            <td><?= $student->userRole(); ?></td>
                            <td>
                                <?php if ($student->isTrashed()): ?>
                                    <span class="badge bg-danger">Silindi (Trashed)</span>
                                <?php else: ?>
                                    <span class="badge bg-success">Aktif</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if (!$student->isTrashed()): ?>
                                    <button class="btn btn-warning btn-sm" onclick="alert('Kayıt Geçici Olarak Silindi (Soft Delete)')">Delete</button>
                                <?php else: ?>
                                    <button class="btn btn-success btn-sm" onclick="alert('Kayıt Geri Yüklendi (Restore)')">Restore</button>
                                    <button class="btn bg-danger text-white btn-sm" onclick="alert('Kayıt Veritabanından Tamamen Silindi (Force Delete)')">Delete Permanently</button>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>