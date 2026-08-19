<?php

declare(strict_types=1);
require_once __DIR__.'/../config/database.php';require_once __DIR__.'/../includes/auth.php';require_role('customer');$id=(int)current_user()['id'];
$stmt=$pdo->prepare("SELECT * FROM users WHERE id=?");$stmt->execute([$id]);$user=$stmt->fetch();$error=null;
if($_SERVER['REQUEST_METHOD']==='POST'){verify_csrf();try{$name=trim($_POST['name']??'');$phone=trim($_POST['phone']??'');$address=trim($_POST['address']??'');$password=$_POST['password']??'';if($name===''||$phone===''||$address==='')throw new RuntimeException('Nama, nomor telepon, dan alamat wajib diisi.');if($password!==''&&strlen($password)<8)throw new RuntimeException('Kata sandi baru minimal 8 karakter.');if($password!==''){$stmt=$pdo->prepare("UPDATE users SET name=?,phone=?,address=?,password=? WHERE id=?");$stmt->execute([$name,$phone,$address,password_hash($password,PASSWORD_DEFAULT),$id]);}else{$stmt=$pdo->prepare("UPDATE users SET name=?,phone=?,address=? WHERE id=?");$stmt->execute([$name,$phone,$address,$id]);}$_SESSION['user']['name']=$name;flash('success','Profil berhasil diperbarui.');redirect('customer/profile.php');}catch(Throwable $e){$error=$e->getMessage();}}
$pageTitle='Profil';require __DIR__.'/../includes/header.php';require __DIR__.'/../includes/customer_nav.php';
?>
<section class="section" style="padding-top:30px">
    <div class="narrow">

        <div class="dashboard-head">
            <div>
                <h1>Profil Pelanggan</h1>
                <p>Perbarui data kontak yang digunakan pada pemesanan.</p>
            </div>
        </div>

        <div class="form-card">

            <?php if ($error): ?>
                <div class="form-error">
                    <?= e($error) ?>
                </div>
            <?php endif; ?>

            <form method="post">
                <input
                    type="hidden"
                    name="csrf_token"
                    value="<?= e(csrf_token()) ?>"
                >

                <div class="form-grid">

                    <div class="form-group full">
                        <label for="name">Nama</label>
                        <input
                            type="text"
                            id="name"
                            name="name"
                            value="<?= e($user['name'] ?? '') ?>"
                            required
                        >
                    </div>

                    <div class="form-group">
    <label for="email">Email</label>
    <input
        type="email"
        id="email"
        name="email"
        value="<?= old('email') ?>"
        autocomplete="email"
        required
    >
</div>

<div class="form-group">
    <label for="phone">No. WhatsApp</label>
    <input
        type="tel"
        id="phone"
        name="phone"
        value="<?= old('phone') ?>"
        autocomplete="tel"
        inputmode="numeric"
        placeholder="Contoh: 082258706624"
        required
    >
</div>
                    </div>

                    <div class="form-group full">
                        <label for="address">Alamat</label>
                        <textarea
                            id="address"
                            name="address"
                            rows="4"
                            required
                        ><?= e($user['address'] ?? '') ?></textarea>
                    </div>

                    <div class="form-group full">
                        <label for="password">Kata Sandi Baru</label>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            minlength="8"
                        >

                        <span class="help">
                            Kosongkan bila tidak ingin mengganti kata sandi.
                        </span>
                    </div>

                </div>

                <div class="form-actions">
                    <button
                        class="btn btn-primary"
                        type="submit"
                    >
                        Simpan Perubahan
                    </button>
                </div>

            </form>
        </div>
    </div>
</section>