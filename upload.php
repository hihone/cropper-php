<?php
/**
 * 保存 base64 的图片
 *
 * User: hihone
 * Date: 2019/3/28
 * Time: 22:36
 * Description:
 */

try {
    header('Content-type:text/html;charset=utf-8');
    $base64_image_content = $_POST['file'];
    //匹配出图片的格式
    if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64_image_content, $result)) {
        $type = $result[2];
        $path = "avatar/";
        $mask = @umask(0);
        if (!file_exists($path)) {
            //检查是否有该文件夹，如果没有就创建，并给予最高权限
            mkdir($path, 0700);
        }
        $new_file = $path . time() . ".{$type}";
        if (file_put_contents($new_file, base64_decode(str_replace($result[1], '', $base64_image_content)))) {
            umask($mask);
            echo json_encode([
                'code'    => 0,
                'message' => '上传成功',
                'result'  => $new_file,
            ]);
        } else {
            echo json_encode([
                'code'    => -1,
                'message' => '上传失败',
            ]);
        }
    }
} catch (Exception $e) {
    echo json_encode([
        'code'    => -1,
        'message' => $e->getMessage(),
    ]);
}