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
                    // 新しい画像が正常にアップロードされた場合、以前の画像を削除
                    $sql = "SELECT profile_image FROM user WHERE user_id = ?";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([$user_id]);
                    $current_img = $stmt->fetchColumn();
                    
                    // 古い画像が存在すれば削除
                    if ($current_img && file_exists('uploads/' . $current_img)) {
                        unlink('uploads/' . $current_img); // 古い画像を削除
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

        // プロフィール画像を更新する処理
        if ($profile_img !== '') {
            $sql = "UPDATE user SET profile_image = ? WHERE user_id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$profile_img, $user_id]);
        } else {
            // プロフィール画像がアップロードされなかった場合はNULLを設定
            $sql = "UPDATE user SET profile_image = NULL WHERE user_id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$user_id]);
        }

        // 興味のあるスポーツを更新する処理
        $sports = ['baseball', 'jogging', 'tennis', 'valley', 'soccer', 'basket', 'tabletennis', 'badminton', 'muscle', 'boxing', 'golf', 'football'];
        foreach ($sports as $sport) {
            if (isset($_POST[$sport])) {
                $level = isset($_POST[$sport . '_level']) ? htmlspecialchars($_POST[$sport . '_level'], ENT_QUOTES, 'UTF-8') : '';
                $sql = "INSERT INTO user_sport (user_id, sport_id, level) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE level = ?";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$user_id, $sport, $level, $level]);
            }
        }

        // リダイレクトを出力前に行う
        header("Location: myprofile.php");
        exit();

    } catch (Exception $e) {
        $_SESSION['error'] = 'エラーが発生しました: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8');
        header("Location: myprofile.php");
        exit();
    } catch (PDOException $e) {
        $_SESSION['error'] = 'データベース接続に失敗しました: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8');
        header("Location: myprofile.php");
        exit();
    }
}
?>
