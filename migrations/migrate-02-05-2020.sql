ALTER TABLE `stem_paginas` ADD `video_url` VARCHAR(255) NULL DEFAULT NULL AFTER `meta_description`;
ALTER TABLE `stem_paginas` ADD `chat_url` VARCHAR(255) NULL DEFAULT NULL AFTER `video_url`;