<?php
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

        // 興味のあるスポーツを更新する処理
        $selected_sports = $_POST['sports'] ?? []; // フォームから送信されたスポーツの配列
        $sport_levels = $_POST['levels'] ?? []; // フォームから送信されたスポーツレベルの配列

        // データベースから全スポーツ情報を取得
        $stmt = $pdo->query("SELECT sport_id, sport_name FROM sport");
        $sports_map = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $sports_map[$row['sport_name']] = $row['sport_id'];
        }

        $pdo->beginTransaction();

        // 選択されたスポーツを挿入または更新
        $stmt = $pdo->prepare("INSERT INTO user_sport (user_id, sport_id, level) VALUES (?, ?, ?) 
            ON DUPLICATE KEY UPDATE level = VALUES(level)");
        foreach ($selected_sports as $sport_name) {
            if (isset($sports_map[$sport_name])) {
                $level = isset($sport_levels[$sport_name]) ? htmlspecialchars($sport_levels[$sport_name], ENT_QUOTES, 'UTF-8') : '未設定';
                $stmt->execute([$user_id, $sports_map[$sport_name], $level]);
            }
        }

        // 選択されなかったスポーツを削除
        $stmt = $pdo->prepare("DELETE FROM user_sport WHERE user_id = ? AND sport_id NOT IN (
            SELECT sport_id FROM sport WHERE sport_name IN (" . implode(',', array_fill(0, count($selected_sports), '?')) . ")
        )");
        $stmt->execute(array_merge([$user_id], $selected_sports));

        $pdo->commit();

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
?>
