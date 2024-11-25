<?php
ob_start(); // バッファリングを開始

session_start();
require 'db-connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $pdo = new PDO($connect, USER, PASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        if (!isset($_SESSION['user_id'])) {
            throw new Exception('セッションが開始されていません。');
        }

        $user_id = $_SESSION['user_id'];
        $name = isset($_POST['name']) ? htmlspecialchars($_POST['name'], ENT_QUOTES, 'UTF-8') : '';
        $self_intro = isset($_POST['self_intro']) ? htmlspecialchars($_POST['self_intro'], ENT_QUOTES, 'UTF-8') : '';
        $place = isset($_POST['place']) ? htmlspecialchars($_POST['place'], ENT_QUOTES, 'UTF-8') : '';

        // 画像アップロード処理
        $profile_img = '';
        if (isset($_FILES['profile_img']) && $_FILES['profile_img']['error'] == 0) {
            $img_name = $_FILES['profile_img']['name'];
            $img_tmp_name = $_FILES['profile_img']['tmp_name'];
            $img_ext = strtolower(pathinfo($img_name, PATHINFO_EXTENSION));
            $allowed_ext = ['jpg', 'jpeg', 'png', 'gif'];

            if (in_array($img_ext, $allowed_ext)) {
                $img_new_name = uniqid('', true) . '.' . $img_ext;
                $img_dest = 'uploads/' . $img_new_name;
                if (move_uploaded_file($img_tmp_name, $img_dest)) {
                    // 古い画像を削除
                    $sql = "SELECT profile_image FROM user WHERE user_id = ?";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([$user_id]);
                    $current_img = $stmt->fetchColumn();
                    if ($current_img && file_exists('uploads/' . $current_img)) {
                        unlink('uploads/' . $current_img);
                    }
                    $profile_img = $img_new_name;
                } else {
                    throw new Exception('画像のアップロードに失敗しました');
                }
            } else {
                throw new Exception('許可されていないファイル形式です');
            }
        }

        // ユーザー情報を更新するクエリ
        $sql = "UPDATE user SET user_name = ?, bio = ?, activity_region = ? WHERE user_id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$name, $self_intro, $place, $user_id]);

        if ($profile_img !== '') {
            $sql = "UPDATE user SET profile_image = ? WHERE user_id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$profile_img, $user_id]);
        }

        // スポーツ名をスポーツIDに変換
$sports_map = [];
$sport_sql = $pdo->query("SELECT sport_id, sport_name FROM sport");
while ($row = $sport_sql->fetch(PDO::FETCH_ASSOC)) {
    $sports_map[$row['sport_name']] = $row['sport_id'];
}

$pdo->beginTransaction(); // トランザクションの開始

// 送信されたスポーツ名をスポーツIDに変換
$selected_sports = isset($_POST['sports']) ? $_POST['sports'] : [];
$sport_levels = isset($_POST['levels']) ? $_POST['levels'] : [];

foreach ($selected_sports as $sport_name) {
    if (isset($sports_map[$sport_name])) {
        $level = isset($sport_levels[$sport_name]) ? $sport_levels[$sport_name] : '未設定';
        $stmt = $pdo->prepare("INSERT INTO user_sport (user_id, sport_id, level) VALUES (?, ?, ?) 
                               ON DUPLICATE KEY UPDATE level = VALUES(level)");
        $stmt->execute([$user_id, $sports_map[$sport_name], $level]);
    } else {
        throw new Exception('無効なスポーツ名: ' . htmlspecialchars($sport_name, ENT_QUOTES, 'UTF-8'));
    }
}

        try {
            // 選択されたスポーツをスポーツIDに変換して挿入または更新
            $stmt = $pdo->prepare("INSERT INTO user_sport (user_id, sport_id, level) VALUES (?, ?, ?) 
                ON DUPLICATE KEY UPDATE level = VALUES(level)");

            foreach ($selected_sports as $sport_name) {
                if (isset($sports_map[$sport_name])) {
                    $level = isset($sport_levels[$sport_name]) ? $sport_levels[$sport_name] : '未設定';
                    $stmt = $pdo->prepare("INSERT INTO user_sport (user_id, sport_id, level) VALUES (?, ?, ?) 
                                           ON DUPLICATE KEY UPDATE level = VALUES(level)");
                    $stmt->execute([$user_id, $sports_map[$sport_name], $level]);
                } else {
                    throw new Exception('無効なスポーツ名: ' . htmlspecialchars($sport_name, ENT_QUOTES, 'UTF-8'));
                }
            }

            // 選択されなかったスポーツを削除
            if (!empty($selected_sports)) {
                $selected_sport_ids = array_map(function ($sport_name) use ($sports_map) {
                    return $sports_map[$sport_name];
                }, $selected_sports);

                $placeholders = implode(',', array_fill(0, count($selected_sport_ids), '?'));

                $stmt = $pdo->prepare("DELETE FROM user_sport WHERE user_id = ? AND sport_id NOT IN ($placeholders)");
                $stmt->execute(array_merge([$user_id], $selected_sport_ids));
            } else {
                // もしスポーツが選択されていない場合はすべて削除
                $stmt = $pdo->prepare("DELETE FROM user_sport WHERE user_id = ?");
                $stmt->execute([$user_id]);
            }

            $pdo->commit();
        } catch (Exception $e) {
            $pdo->rollBack();
            throw $e;
        }

        $_SESSION['success'] = 'プロフィールが更新されました。';
        header("Location: myprofile.php");
        exit();
    } catch (Exception $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        $_SESSION['error'] = 'エラーが発生しました: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8');
        header("Location: myprofile.php");
        exit();
    } catch (PDOException $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        $_SESSION['error'] = 'データベース接続に失敗しました: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8');
        header("Location: myprofile.php");
        exit();
    }
}

ob_end_flush(); // 出力バッファをフラッシュ
?>
