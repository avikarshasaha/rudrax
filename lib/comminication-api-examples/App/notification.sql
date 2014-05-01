CREATE TABLE IF NOT EXISTS `notification` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `recipientUid` int(10) unsigned NOT NULL,
  `eventId` int(10) unsigned NOT NULL,
  `isNew` tinyint(1) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `IX_recipientUid` (`recipientUid`),
  KEY `IX_isNew` (`isNew`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='User notifications';