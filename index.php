<?php

// 카페24: /www 에서 index.php 를 먼저 읽는다 (기본 안내 페이지 hosting_index.html 보다 먼저).
// /www 가 프로젝트 루트이고 실제 앱의 시작 파일은 public/index.php 라서, 여기서 그대로 넘겨준다.
require __DIR__ . '/public/index.php';
