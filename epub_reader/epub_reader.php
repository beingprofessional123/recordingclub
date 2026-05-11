<?php
header("Content-Type: application/json");
if (!isset($_FILES['epub_file'])) {
    echo json_encode(["error" => "No file uploaded"]); exit;
}

$epubFile = $_FILES['epub_file']['tmp_name'];
$zip = new ZipArchive();
$contentList = [];

if ($zip->open($epubFile) === TRUE) {
    $page = 1;
    for ($i = 0; $i < $zip->numFiles; $i++) {
        $name = $zip->getNameIndex($i);
        if (preg_match('/\.x?html$/', $name)) {
            $html = $zip->getFromIndex($i);
            $doc = new DOMDocument();
            libxml_use_internal_errors(true);
            $doc->loadHTML($html);
            libxml_clear_errors();

            $body = $doc->getElementsByTagName('body')->item(0);
            $items = [];

            if ($body) {
                foreach ($body->childNodes as $node) {
                    if ($node->nodeType !== XML_ELEMENT_NODE) continue;
                    $tag = $node->nodeName;
                    $text = trim($node->textContent);
                    if ($text === '') continue;
                    $type = preg_match('/^h[1-6]$/', $tag) ? 'heading' : 'paragraph';
                    $items[] = ["type" => $type, "text" => $text];
                }
            }

            $contentList[] = ["page" => $page, "content" => $items];
            $page++;
        }
    }
    $zip->close();
} else {
    echo json_encode(["error" => "Unable to open EPUB"]);
    exit;
}

// — Remove initial empty pages — 
while (!empty($contentList) && count($contentList[0]['content']) === 0) {
    array_shift($contentList);
    // optional: you may want to re-number pages
}

// Optional: re-index page numbers
foreach ($contentList as $idx => &$pg) {
    $pg['page'] = $idx + 1;
}
unset($pg);

// 🚀 Return the cleaned list
echo json_encode(["pages" => $contentList]);
?>
