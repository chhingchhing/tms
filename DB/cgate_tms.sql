-- phpMyAdmin SQL Dump
-- version 4.0.4
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Mar 17, 2014 at 09:18 AM
-- Server version: 5.6.12-log
-- PHP Version: 5.4.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `cgate_tms`
--
CREATE DATABASE IF NOT EXISTS `cgate_tms` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `cgate_tms`;

-- --------------------------------------------------------

--
-- Table structure for table `cgate_app_config`
--

CREATE TABLE IF NOT EXISTS `cgate_app_config` (
  `key` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `value` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `cgate_app_config`
--

INSERT INTO `cgate_app_config` (`key`, `value`) VALUES
('additional_payment_types', 'Cash'),
('address', '123 Nowhere street'),
('automatically_email_receipt', '0'),
('barcode_price_include_tax', '0'),
('company', 'Tour Management System'),
('company_logo', '1'),
('currency_symbol', ''),
('date_format', 'middle_endian'),
('default_payment_type', 'Cash'),
('default_tax_1_name', 'Sales Tax'),
('default_tax_1_rate', ''),
('default_tax_2_cumulative', '0'),
('default_tax_2_name', 'Sales Tax 2'),
('default_tax_2_rate', ''),
('default_tax_rate', '8'),
('disable_confirmation_sale', '0'),
('email', 'youlay.hong@gmail.com'),
('enable_credit_card_processing', '0'),
('fax', ''),
('hide_signature', '0'),
('hide_suspended_sales_in_reports', '0'),
('language', 'english'),
('mailchimp_api_key', '2bd6d108cdb700deef4eec8d1ca5e5ff-us3'),
('merchant_id', 'admin'),
('merchant_password', 'password'),
('number_of_items_per_page', '20'),
('phone', '0972792217'),
('print_after_sale', '0'),
('receive_stock_alert', '0'),
('return_policy', 'Codingate'),
('round_cash_on_sales', '0'),
('speed_up_search_queries', '0'),
('stock_alert_email', ''),
('time_format', '12_hour'),
('timezone', 'Asia/Bangkok'),
('track_cash', '0'),
('version', '13.2'),
('website', 'condingate.come');

-- --------------------------------------------------------

--
-- Table structure for table `cgate_app_files`
--

CREATE TABLE IF NOT EXISTS `cgate_app_files` (
  `file_id` int(10) NOT NULL AUTO_INCREMENT,
  `file_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `file_data` longblob NOT NULL,
  PRIMARY KEY (`file_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Dumping data for table `cgate_app_files`
--

INSERT INTO `cgate_app_files` (`file_id`, `file_name`, `file_data`) VALUES
(1, 'codingate-logo.png', 0x89504e470d0a1a0a0000000d49484452000000aa0000002b0806000000e89a13ff00001ac749444154789ced7c7b781cc5b5e7afaaab7b7a5a33a3d1fb6121644996842d6c631be3280ed7f812de1730e6151e9b84cb23c94d022c77bf2cf0ed927b939b64bf9090ec4d96607080dc0d84808301638331c636c436b6b18d2c6cc9d6cbd2e8351a8d46333daf9eeeaafd632459b246d2c8c1bb663ffdbe4f9e714ff5a953d5bf3a75cee9d30dcc6216b398c52c66318b59cc6216e716c8d912fcd37bca943575b6c2aa225ac82d0e0010c3ff9031bd0a08000444002627665f50f1addbe20d64d81dc1c79e6f4e293b4796f1e4dcf9da72d3969b6520db661125e5880420c8a9c38c12345a51ef77075bbbf7c742c6e739de599c5db0b325381c436e3c810785c0b7080513042064e2ba20c3cc22002847d0a199dbae5e66df9393a96efea03eb7fdbd4f7ce6d8f625b28dfdcffcca928ab8bcca2570ad4d90153248ae20a029848f820b01003081df09e0a7007a3fbfd1cee26ce3ac11b5374095c120dc9c430d2784391013ba298439c52954a19465db71f38a0bd46b02617ed1bfdc99fbef42d0fa6d07bd26001433853d9e7d5e6d25b13d70bec96e3588700485153184080a2e26174c087510eab013498d10eee004caa48d67714ee2ac11d5b404e51c4c088a6327b9e7955d43bbbc8188174861f900000405d94ef7a5b5cef90be75a5525b9d2cd738b257ae5d28c1f6d3b886600b8bfe0bc9215c4f9c079906fef23a6f9a99238b02f123cde19d4bd96107c325db2549bfb567beeca8ba1d55ac9fe27d16116e72ace1a5109212034f96998c473b85dbcb2e370e8e8f0cf2989c2a48863f3fe60d5d756655dfb8d2b326ecacae4ab96cfb37d7cd9e2828ea6a62056aaae4b4be2cae58354608308bdf3d2a0f7ad463d783c91b0fc0026256a9dabb0ec16420a09500b0cfbcab3f842e1ac1175144420c3266265f98a1740c7544d4dcb644d9da18e7d4db6c0e20a5bd535cb9465a579e2cbf96eba392b2737569890be2c83947c86d8813d46e8958660e02361091dc0542e056a1d4ecd11a7b1532acd52f58b86736d0b34010405b11f0f84502f38142189b2ac0c51b234c355ac70945b94287d9671b43f1e6f105c04310d498164c026003a4acf1441dd2cce6d9c7d8b7a06188a42a7949f3485302d70872a0b5726a1aa20701992885942f4064d4387987cbb1f0732ee6376ebff02e25cb3a800004f7fc86034e14b98dc1c261523028a00140170899038489a2405703a3567ede9170f678da851039c7318004028289378da7d797a874c859abac9390708b80068d2fa3392549acf846c6eca1823601611dca2e02626cf10cce2dcc459236a6b4f38123112de98c50d59155a691ed3d23d970b0199818f35840267be651713c9a110e24e101851063d4ea7f76b67716ee1ac11b5b123a0f7f8455bdf90f017b8a5c24b6a32caca8adc6abae793e9b6f63403a29adc6c362fc14ab320958704f77bc1db066145d2d56316e706ce1a5183e1b871b015ed7d7eb4676b28a99a837ff8d9370ae65f323f339d008e67a8d4e4a983250ec08c086bdaed7b6d661efd4f4a568ddbc0da2c48c53d48741c8aeb47872c4b9ff18066f1ff146733eae7db0e0d1c2fcdcdd99e97652b2f2bc055573ba5ecf3f30adf3bdc92b1fb1f2ed5828519d284343d9580637d266b6a8e550a013666bfa7004005182c71fe7f558a16ae2dca091232de251042a0db32b04104d45b32f397390db1c66592e53e6a05f791d8473b2281c6783c31bbf57fc1705689dad215f2bef481780b24a7f8b6bfd36e2c2be22b97d488254baa9d3ab5c06161380417235fc04da02a97a122dfa14920ea50749486dca2806c08f50664de4514dc2880d13bfc4909c9ffcd61363c82424a74a151893a7a6c3cb685ebdb5ed1fbdf680beb5ecc26fcbf7038ab79542144acb1533fba6eb378f66887c3b77a49e6e5cb2bd5ca8a398962d926c6b81d13fd4d4600215140115496085528854a24aa11890a21dc2070a7a41b494ab32878b3c6f50322d2f0616870d7beb8fe7a5b44af374d2b96eab4599cdbf8bf9452246a6686549261936b33edb42653b3ce93a8500032b58f4c60c64db474fbaccd5a18c811f40a265081347c6b019821095d83148d21931fd5c13bac59927e61312551bb3d1ed8353bb56b1a183bddf88e6cd723762d292a1e37f0ded6adfcc6356bc6b5ce2eb6a3ac369b12ca592468a86662eabcaa104932c6c396d9df1935b4826a53cd2e604634ac723e7d1a54921857dd3986a36ab1a995567347691524cd01bdb3194deb9ee02b2b8bf0d2cb2f53a7d38923f5f5f0f9faf9ae9dbbb46f7fe7dbe637efb9c7d8b2e59d69fb381d3b767c4057aefc0a0821b879ed5afefac68de37eff68d74ee4e5e7a3a0a08066381c13ea73859898cc2084201e8f63fefc05bcbdbd7ddc6f271a1ba139326856763614459954c6a96304861187cfe74369e9f91326f1473ffa57dc79c71d282a2aa24c96c7e9974a2e00504a71f8d37a3cbb6e1d7ffae9a7c7fdf6ce962da89e57498bcf3b0f92244d2ae374793b77eee2dffbdef7d0d0d0307a3ce5d6fffa86d72863328bc562f99a23633121a474625b72dae7c880846e59d66e008d005054e9c2f50f57337d30eeae5c9a53999127d580c28129aa9d46a4528998465834bffdabe6bd62e593357266ee0aaa6a2a78da09fb53257d8227675bf07a40ece39c3321c42a00a596651d364d735f381c5e62b3d9eeaeadad7ddab278c3d6ad5b67147471cb5a096021e7dc6f59d62600c191dffef0e28b944a924351943242692d80dc09639ee42272cebb85109b014400e0973fff39f20b0b1917225b62d27c005500d4c9648c7ba2428073ceeb01ec1adbe62f1b5e65b2627330592e07218b0138d2d10d0004e7cd02d80d2000000f3c701f56afba4cc9cfcf733359ae05301f004d33a3c82dd3dc218468c4983a8e0944fdc52f9ea44ea7ab5455d59b2cce6fb1d96c358410775a5d00304db3772810f837008d363bc357ee2c75d832a4555ab6f6754ead15f1280a41c4b4be31014028e5619ff5a6ee333b6c7ae02aaaa84f08c0315591f4e410901415563cf60240daa3d1a8231a093fee743a970f0efa5ff0f97c0d9ece4e87969171cf638f3eb6ec974f3df5e843dffffeae6baebb2e5d7781eabafe8010e2d66838ecd375fd2880c3755ffa121e7ffc31c56ed7163a1cce7f746765ad5614a514c3c49a566b21108bc5ea8510870134df7dd75d74fe82056e2a49573159becde970ad608ce5a73b0700b861187f00b00f400c00ded8b851cdcaca5a959d93fd4da7d3b94c92a4524208136382ce544f6724f50362b1d866cb343d00028b162ec49a356bb26589dd515050b0d6e572d5524a272cca542084807381c1c1c14738e71d18b3d0c711e69ffee93bb4a0a0a0b2b864ce7fa9ac9c77bb699a6a580f47e246dc27a6284c3ed51185aeebbece8e8e18007ad9d7cb1d3973ecb76795d81e559dac341ee6b1c8502228ac292bfd4128618a5d72c82a100f0b1e1eb234391a0684306199dc8ae83ab74c03d358e53113c0249b5d83a2aae01613008d46a34a2818347273f3ccc1c100ebeff3221a8fabf1582ce2cacc5cfce0f7bfffdb8ef6f67ffb68d7cebfdc7ec79d418fc7335d374c0fe91c0082a160440f85340058bbf626d5e572adba60fefc273233ddcba39188111c0ac64ccbd4d39a53000303035ed334e982050b505757975b3677ee374a4b4b1f94182b8cc5a24638100e241209732a799452e674381d5492601886014001107be4e1fface4e4e45cbf68d1a2c7ed9a363f1c0e9b7ebf5fb72ccb608c39727272b4783c6e0602011d80713a7929a518f0f982a1609003c03fde734f6e7979f9a373e796dfcbb97084f55024a4eb3e9e86bf462885e09cf7f6f69a0963fc236da344cdcfcb436e4e4ef6dcb973efacaeaeb963c0e7e37bf7ec39b8e1b5d7f66eddb6ad6928184ccbb20821fca669d6d77ca950295f927569de5cf5db8a9d16777d166d6eddaf1f3eb2a3eb93deb6c1a90a9df99c2a77eeea6f56dc3d6f85bb665c159fc440c201bfefddffbdb17dc79bfb138691d6d69c35a72c77ee6d0fdee05c74695d52c9a4a5126373b4840c5b0d024208cdcaceae74381c3f6d6d6d2df9f5af7ffdc23ffff323bd6d6ded337205aebaf24abaa0764179d9dcb90fba5c99cb5a5b5a7adfdef4d6bed75fdf78a8e1e8d1de783c9e8e3c2e84f044a351ef155ffdaaba6cd9d2cb0b8b8aee2384e4d77f7ab875db7bdb1a3efce8c3a39f1c3cd4190a4dfec0e225cb2f2efcef4f3c71f7f2e597948f3d5e595151535353739f6ab7d7767676fafef2da6b7b376dda74f8d0a79f9efcce030fdcf0a39ffce4fa2e8fc777d75d77ffbee1b3cf9a30deb8510026e7bc3d914878aeb8fcab4a5d5ddd5da5e79d7f573c6ea8f5f59f1e7ff69975bbb66e7befc8e06060fabb81c99027629a667d229118c7b7d14ee7cd9bc7cacaca6af2f3f36f0c854274d7ce9d1ffde0d1479f696969a947d2f748f7227100919abafc122d4b5e6377c9b53d4dd1e67daff7ae3fb0b9633bb7cc5e0cfb5a93a1689eab5c62b81664e2164f65c5d00a4b3fa5946ee2462cad5ba19985c5e5cc9179f1a9c04f4c1a4512026a9a093ee0f3c5321cceecf28a8a8709a5731e7fecf1678c78bcf13bdffd6e7a0b16c0d28b2ed220b03a2f2f7f6597c7e37dfef9e7fff4cba79eda60184607927390aeaf6dda6cb6d8a2450b2b55bbfd0687c351de505fdfbcee99752f3effc20bdb4ccb1a99d394d7880058ba64c97c4db55f0b6094a8ab57ad522e5e71f1e54c9617f67477059e5df7cc9f9e7b6efdabdefefe760011bbddbe8800c8c8c888d96cb63d9148641752675c0c00b165cb962e9c5b517e8bc458eef6edef1ffee10f7ff8db039f1cdccd39f74da65b0af06179e3da8f12b5a0a04095156579454565d5c9f676efdebd7bdf6e6969d98ea49f30a36aa3b205c534e73c7b9962a7cb1271cb08f41abb3b1b429bb86536a7a370c1f9194142604e484a080141289734a7ae66b882d16020ad5ba18edc822065ec54bfa7ddcd4a8a4e1e4946d986f9c6c637ea8b4b4ae85756aeac2d2d2dbd4bbe6c557ec03ff8f473cfaedb7bef7df74fdbaf1002cb975fec9e535cfc55c1b9eaf178f6bdfaeaab1b0cc33880e48598118a0a0b9579f3aa6a0af20b16eb7ac8686e6df9e8cd4d9b369a96d53c9d3cbbaad2eaeaea8034760e00949595156766ba2fb6dbd5ec9d3b0eed3a76ac7183b7bfff00867d5726cb06088124c9dc66b3e918e333a6005db56a559dcce4b25030686c7d6fdb1b9d9eae7738e7bd98217f520a1ff9525252a212422b84102ae756ef9123f5077106240500a64095645a66772af9b190198c846247bc9ec10ea4bfaa863149d0c42d3ac30c301d2b8b8c3ea43d06848cb6a0949a8d4d4d077ef5d453fffeeebbefeed643215e767ed9751595954f5cb464c9f52fbff4c75c559d3a16124220a8ebee0c87637e3c1e8ff8fafb8ff7272dd528a97a7aba593c1e578510da647f9c73cd300c5596652d1e8f97699a5638383818f00f0c34ad5e7d99371a8d30cef9a4e71b86a1fdfef9e7b56028a412323e6f5d5d55552a51a9d4324d0c0d051b83c1602b86497a6ade8034ebd65457a6eb0242a9abafafd71b09878f3ef4d083115d0f4d39be547f1f7ffc315bb870e138e10c006459c6bcca4a96979b9b2d04e7549202c79a8e77e30c570265508ca8954f0834cb103e2b069f9530d3b722a79e1999748ac40c6e830a214e2f0898d0662c7109213c3333b37fc7ce9defc4e2717d68682870c595575c3a674e495d45454576c248e4ffea574fbd76b2fd64f74f7ff6b39473c485406068c8a1d86c6e2e84118bc57c9cf35157455555eaebef9fef72b9963326b9261b0f2104a669fa12a6b99d73eea094aa9a5d33962e5bb6f4c6356ba82ccb06e7d6a4632784e0ea6baea19d9d1d45596e77f15815339cce6c8b73772c168f00a26b6868e86fa92a638aa2b82825cce5ca54eebdefdebfafa9a9295355955b567af6692446f0fbfddb2dcb6ac0e9e9a964b028982c33359994a5464f4fcf992b4dc08425140014145c62c4c44c489f4c850ce73f53f22a954d9c5c1c9592290920b5b0e1e36325524a39e7dcbf7bf7ee5d115d0ffafd7eef35d75e735565e5bcaaf28a8a87b3b2b20aba3c9d7f0c8582c77ff3dbff3571110a20914828922431000621888a31ef35b02c8b0d0d055759a6f50380144f9663144220120e1f304db39e312633c6585e7e5e6e7e41fead42e056404cb96809015c2e17e6cf5f00008846a3c648aa293f3fdf2133a6599669c8b2ecf3fb07fe96b7c730bbddae522ab1a2e2e2c2a2e2e26f8d4c44fa462519d046a3d14700b422557a2a1c89d0910cc248dae14c4109818090cff4193a662394ca5000c1b925628998f537bd7e47766429844a0a2c13c44a70c24d08c8e31b4d549622b9b80287ebebf70d8542817e5fbfef86eb6fb871f1e2c55573e7cebd373b3bbb386e24d6bbb3b2f7fdf8c73f9eb010b9658d2e004a292763162bb72cba67f7eee06020d0a8699a5f8853b50f84104e0861d5d5d52579b9b96a6f6f8f6e591623048ac4243a3030a0379f68f686239174023b4e0881aaaa4a454579b1d3e91a79f906edf478744269abaadad07eb2a33b180c4d6afaa6231b210436d5ce2489526f5faf7ea2b9a5d7308cd8d871a50349928c83070f06745d1f379fa34435138971dbe34cb6d6891069ba35135152954f6d0ee676e6dab2ad0437f4c1b877c8170de689d36eb9a6b908082150f24a5c545173891937683ca20b236a00f6545aa7face01e86d6d6d475f78e1c5c8a0df3fb066cd4d6bebeaea9664e7e4dcbcece28b0b63b1d8d377df7df7f669f3a2631683c5b9f9ec73cfedce74bb839224b9c737235cd3b4fc279ffcf9c3595959d4d3d9e9210411ce058510e8e9eee95ebf7efd5f8e3434b41042d2daa96aaaab0befbfffbebb172dbea87ce4daae5fbfbe5ed3b4a70921e6d0d0507d28343951d3001582532180d69696eedffee637ffd17ef264f74c85108298d7db7fb8afaf6f9c711a26ea445659d6e47e4f1adde14ceb5daa56b835459596bbf36dc5d15022d0db1c3911f086f414617a5af2ca57acd64856c17cc9e12eb722219f35e46db1c2c108484e4aad537d1f46cceff7b7befca757360e0cf8fd838383b7acfefbd52b8b8a8a567fa9ae2e57d3b4fc5834e29a4a176b7ccedb3c7ee2443b92efc09a60757ef8c41337959d5fe6321309a3bebefe98c3e1080249eb242b4a4066ecc38f3ffe782fd273a968dd8a1535849071e9a9a3478f7600f00dcb88618a6077b23b53293ba32c20cbf28ebd7bf71e4d53bfb118d1251551c7161f0810424c8c8ffe668444228111075f58e089b8e0d3297cdb7f5b482d9397a84eb6a264bee34e50a105fbf9c1b64f4207c72b2d386572cc8a47a79477e5af3752dfc916b725c86ab8f26ea3367b7eb4b76df750737d7d221c4a995e9ac4a28e85118bc5badf7cebadad7ebfdfd7dfdfefbfeeba6baf2aafa85cbc74e9d21fe87a48a1297c2691dc7e0dcb9ae0c21838ed82bcf0fbdf6bde7e6fedcd6b6ffe9ad3e5523b4f9eecddf6fef67a87c339da4e9665b3b0b03082647e7b5a2264d8edb4a2a23c28cbf2e96d4d4c9d723a358619ecb08c31e392e5cbbdaf6fdc38610b3f534cb8e74e08a18c49e5070f7ef2addada0b23e377b3e1f2e42499530a34e209fd7ffce2278dc7f9268b0b80a9c45d54a35efdc4fb9716da3218ac044f0642631eb2976482b64301965d9c7191e69216331bad1cec31bb3fdbd1bfa5f5b0b779b4a110104c71c15d7075ed0f5f2a9633738cd16293117d8480158bc0d7ddaa38aa96ce154c5949985c9918eced0d1f3ff89eaf61ffd1783c664c1bf94f3e672600df477ffdebee818181e080afdf7ffd0d375e7fd1922565aecc4c7a7a0a08001445d6e65654fcddbe7d1fe3c20b179ac3c1ebb85e0cc3c0be7dfbb1f0c2da22cbe22b5d99ae65e1b06e6cd9b279c7c143871a4a4b4b4fb54f56964cae610a703e335ff174ccc4a2e617e415575f50f375afb7afdf6653f8d41c1f5b85978c8db66fff60fb430f3dd4d8d0d030b1284596654892442549427676765566a6fbc1a4532fcd68400933e255a8ed452a419618a1ce5c393bc3cd6ea432bd0200284d218f00e72f725370e21244a87dcdf1f623db7d7f3efc6ed7e668381a00c0a8c41895244aec4e87567ee17544922f07014f45382a2b60f316510268c24c6889ded6eee0677b367af76fdf1cf276fb927a50d86c364628a512a59431367a2c615a26636caa817300fab1c6c6c3434343c1debebe9eb56bd77eedf2cb2f5f08c6460921cb0a956599a9aa5dabaeae590560f964736ab3d9b078f162646464a88420dbdbd71779f3cd37b7bff887ff78d9e7f375979595b909252054022184cab23c23e2298a0c496294520a4551d23a970d8fc566b3b1543b458a31504a290a0a8b4a1c4ed7fd8a623308a1337e314d30188c0921da018cee7cc3e929819ede5e7eb2e364b0a9a9c9cf39a748b3ba672c0801f450586d6f6b6701472cec6dd37d54e62e080221a04db57d5089f08176deeaf3848ff71c0f7ed0727070c790576f45d282b188b74be74cf15335830faf4075427e748c1e04c2e403dd1ea3a7ad31d079624fb8e3f88e81b6a64621925b6d341a354f9c38e10f47227e4f5757a0adb515a1502876ecd8311f1780d7eb1d9a66b81c40a4bba7e7f89ffffcaadedbdbe7edededfde66db77f6da5a2d8981002dd3ddd46d3f1e3beecec6c374f567c4d39a79452747777079a8e1d6df8ecd8b1037b76ef79f7e0a143070018d168947b3c5d91c6c6467f97c713e8f37ad3ce8470cee1f17499f9f96d012a51bfc7e349e78e1eedebeb0b363535f90383833e5dd7a7edafa9a929100804fc84500680f5f4f4cce8099264f594659e38715c8b4622e316060300cbe278fffdf783c78e35fef1ddadef1d324df38cb689e145e76f3872a45e677db4ab31e0b72cee4a27034025628407e00bfa62edde9343ad00fc38e5bff19ebf6efe48d29c3101e24a27a540204c120df9cc416ffb90b7abdd8cc77d18e377777577773fb36edd33764d7bbbbbababb1b5b5d5b0386ffcf9934f3e61595c696a6cdc8b5329aaa960842311cf9677de79a7dfe70b0cf87cc1eb6fb8b1d2e5722a1f7cf041634747e7bf70ce73d3f1f128a5088542812e8fc773acb1b13d1e8f7b305c17d1d3d313d9b061c3bbfbf7efefd375bdb7a5b9b9755a81c3882712d8bc658bf7d0a787d73b9dce2de170645c327d327576eedaf5765f7f7fbf699abef6f6f629fb1342c47ef7bbdffd9131f913ceb972265923420864598eb4b5b5eef5f97ce362a471396e001a92abfe4cfd198ee404c48665a848ffb92c73ccb9a96e10a8c3fa7d5ef2d8b04c05c90511193ea60deb1ec1cc024a0ac071414df5c2baba2f2fdef5e1873b4e9c38d1886401f24c5e1c6c8cd1792c9946e6531d3e3e6911ca2418199b8253639b6e118ecc793afd7d1efcc198bec695714ee63dfc2d1d9d3ef87465cda46afff394f779432d2a2c748423914830181cb9bb3793f9fcffe175439f277f66318b59cc62169f2bfe0f0ba21e937c5b0b090000000049454e44ae426082);

-- --------------------------------------------------------

--
-- Table structure for table `cgate_commissioners`
--

CREATE TABLE IF NOT EXISTS `cgate_commissioners` (
  `commisioner_id` int(11) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `tel` varchar(100) NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`commisioner_id`),
  KEY `first_name` (`first_name`),
  KEY `last_name` (`last_name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=18 ;

--
-- Dumping data for table `cgate_commissioners`
--

INSERT INTO `cgate_commissioners` (`commisioner_id`, `first_name`, `last_name`, `tel`, `deleted`) VALUES
(1, 'Dara', 'Chan', '0972792219', 0),
(2, 'siyna', 'ea', '0972792319', 0),
(3, 'Dara', 'chean', '0972792220', 1),
(5, 'long', 'term', '090461709', 0),
(6, 'welcome', 'hello', '012345678', 1),
(7, 'chanda', 'lann', '0972792213', 0),
(8, 'new', 'commissioner', '0972792223', 1),
(9, 'Sreylin', 'long', '0972792215', 0),
(11, 'phalla', 'chreu', '098989878', 0),
(14, 'Darak', 'hol', '9727922194', 0),
(15, 'Darak', 'hollak', '09727922134', 0),
(16, 'phalla', 'chheoun', '092121235', 0),
(17, 'zinan', 'hero', '092123434', 0);

-- --------------------------------------------------------

--
-- Table structure for table `cgate_currency_types`
--

CREATE TABLE IF NOT EXISTS `cgate_currency_types` (
  `currency_id` int(10) NOT NULL AUTO_INCREMENT,
  `currency_type_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `currency_value` decimal(15,2) DEFAULT NULL,
  PRIMARY KEY (`currency_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Dumping data for table `cgate_currency_types`
--

INSERT INTO `cgate_currency_types` (`currency_id`, `currency_type_name`, `currency_value`) VALUES
(1, 'dollar to riel', '4000.00');

-- --------------------------------------------------------

--
-- Table structure for table `cgate_customers`
--

CREATE TABLE IF NOT EXISTS `cgate_customers` (
  `customer_id` int(10) NOT NULL,
  `account_number` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `company_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `hotel_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `room_number` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `taxable` int(1) NOT NULL DEFAULT '1',
  `cc_token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cc_preview` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `deleted` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`customer_id`),
  UNIQUE KEY `account_number` (`account_number`),
  KEY `deleted` (`deleted`),
  KEY `cc_token` (`cc_token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `cgate_customers`
--

INSERT INTO `cgate_customers` (`customer_id`, `account_number`, `company_name`, `hotel_name`, `room_number`, `taxable`, `cc_token`, `cc_preview`, `deleted`) VALUES
(1, '22342943097', 'AVDA', '', '', 1, NULL, NULL, 0),
(2, 'this is my accout', 'Codingate co,ltd', '', '', 1, NULL, NULL, 0),
(91, '098700', 'Codingate', '', '', 1, NULL, NULL, 0),
(93, '00909', 'Svay reing company', 'AA Hotel', '012A', 1, NULL, NULL, 0),
(123, '0090909', 'lanak Campany', '', '', 1, NULL, NULL, 0),
(124, '0987777', 'Gala company', '', '', 0, NULL, NULL, 1),
(125, '08797865', 'dinner organization', '', '', 1, NULL, NULL, 1),
(161, '12', 'C&C customer', '', '', 1, NULL, NULL, 0),
(162, '100111', 'Codingate', '', '', 1, NULL, NULL, 0),
(163, '100112', 'Gala company', '', '', 0, NULL, NULL, 0),
(164, '100113', 'dinner organization', '', '', 1, NULL, NULL, 0),
(165, '8000', 'Codingate', '', '', 1, NULL, NULL, 0),
(166, '100114', 'AVDA', '', '', 1, NULL, NULL, 0),
(167, '100115', 'lanak Campany', '', '', 1, NULL, NULL, 0),
(168, '100116', 'Svay reing company', '', '', 1, NULL, NULL, 0),
(169, '9000', 'Codingate company', '', '', 1, NULL, NULL, 0),
(170, NULL, 'chen coltd', '', '', 1, NULL, NULL, 0),
(171, NULL, '', '', '', 1, NULL, NULL, 0),
(172, NULL, '', '', '', 1, NULL, NULL, 1),
(173, NULL, '', '', '', 1, NULL, NULL, 1),
(174, NULL, '', 'Mekong hotel', '12', 1, NULL, NULL, 0),
(175, NULL, '', '', '', 1, NULL, NULL, 0),
(176, NULL, '', '', '', 1, NULL, NULL, 0),
(177, NULL, '', '', '', 1, NULL, NULL, 0),
(178, NULL, '', '', '', 1, NULL, NULL, 0),
(179, NULL, '', '', '', 1, NULL, NULL, 0),
(180, NULL, 'Reasmy Group', 'BB Hotel', '010A', 1, NULL, NULL, 0),
(181, NULL, '', '', '', 1, NULL, NULL, 0),
(182, NULL, '', '', '', 1, NULL, NULL, 0),
(183, NULL, '', '', '', 1, NULL, NULL, 0),
(184, NULL, '', '', '', 1, NULL, NULL, 0),
(185, NULL, '', '', '', 1, NULL, NULL, 0),
(186, NULL, '', '', '', 1, NULL, NULL, 0),
(187, NULL, '', '', '', 1, NULL, NULL, 0),
(188, NULL, '', '', '', 1, NULL, NULL, 0),
(189, NULL, '', '', '', 1, NULL, NULL, 0),
(190, NULL, '', NULL, NULL, 1, NULL, NULL, 0),
(191, NULL, '', NULL, NULL, 1, NULL, NULL, 0),
(192, NULL, '', NULL, NULL, 1, NULL, NULL, 0),
(193, NULL, '', '', '', 1, NULL, NULL, 0),
(194, NULL, '', NULL, NULL, 1, NULL, NULL, 0),
(195, NULL, '', NULL, NULL, 1, NULL, NULL, 0),
(196, NULL, '', NULL, NULL, 1, NULL, NULL, 0),
(197, NULL, '', NULL, NULL, 1, NULL, NULL, 0),
(198, NULL, '', NULL, NULL, 1, NULL, NULL, 0),
(206, NULL, '', NULL, NULL, 1, NULL, NULL, 0),
(207, NULL, 'assets', NULL, NULL, 1, NULL, NULL, 0),
(226, NULL, '', NULL, NULL, 1, NULL, NULL, 0),
(227, NULL, '', NULL, NULL, 1, NULL, NULL, 0),
(228, NULL, '', NULL, NULL, 1, NULL, NULL, 0),
(229, NULL, '', NULL, NULL, 1, NULL, NULL, 0),
(230, NULL, '', NULL, NULL, 1, NULL, NULL, 0),
(231, NULL, '', NULL, NULL, 1, NULL, NULL, 0),
(232, NULL, '', 'Mekong hotel', '12', 1, NULL, NULL, 0),
(241, NULL, '', 'Mekong hotel', '12', 1, NULL, NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `cgate_destinations`
--

CREATE TABLE IF NOT EXISTS `cgate_destinations` (
  `destinate_id` int(10) NOT NULL AUTO_INCREMENT,
  `destination_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`destinate_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=10 ;

--
-- Dumping data for table `cgate_destinations`
--

INSERT INTO `cgate_destinations` (`destinate_id`, `destination_name`) VALUES
(1, 'PP - Takeo'),
(2, 'PP - Kampot'),
(3, 'PP - BB'),
(4, 'Phnom Penh - Kampot province'),
(5, 'Siem Reap - Kampong Chhnang '),
(6, 'Phnom Penh - Siem Reap'),
(7, 'Phnom Penh - Siem Reap'),
(8, 'Phnom Penh - Siem Reap'),
(9, 'pp - prey veng');

-- --------------------------------------------------------

--
-- Table structure for table `cgate_detail_orders_bikes`
--

CREATE TABLE IF NOT EXISTS `cgate_detail_orders_bikes` (
  `orderID` int(10) NOT NULL DEFAULT '0',
  `item_bikeID` int(10) NOT NULL DEFAULT '0',
  `issue_date` timestamp NULL DEFAULT NULL,
  `date_time_out` timestamp NULL DEFAULT NULL,
  `date_time_in` datetime DEFAULT NULL,
  `line` int(3) NOT NULL DEFAULT '0',
  `quantity_of_bike` int(10) NOT NULL,
  `number_of_day` int(10) NOT NULL,
  `actual_price` decimal(15,2) NOT NULL DEFAULT '0.00',
  `sell_price` decimal(15,2) NOT NULL,
  `deposit` decimal(15,2) DEFAULT NULL,
  `balance` decimal(15,2) DEFAULT NULL,
  `room_number` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `recieve_from_office` int(11) DEFAULT NULL,
  `discount_percent` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`line`),
  KEY `fk_detail_orders_bikes_items_bikes1_idx` (`item_bikeID`),
  KEY `fk_cgate_detail_orders_bikes_cgate_orders1_idx` (`orderID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cgate_detail_orders_tickets`
--

CREATE TABLE IF NOT EXISTS `cgate_detail_orders_tickets` (
  `orderID` int(10) NOT NULL DEFAULT '0',
  `ticketID` int(10) NOT NULL DEFAULT '0',
  `line` int(3) NOT NULL DEFAULT '0',
  `issue_date` timestamp NULL DEFAULT NULL,
  `seat_number` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `item_number` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `item_vol` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `time_departure` time NOT NULL,
  `date_departure` date NOT NULL,
  `hotel_name` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `room_number` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `quantity_client` int(10) NOT NULL,
  `quantity_purchased` decimal(10,0) DEFAULT NULL,
  `item_cost_price` decimal(10,0) DEFAULT NULL,
  `item_unit_price` decimal(10,0) DEFAULT NULL,
  `discount_percent` decimal(10,0) DEFAULT '0',
  `description` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`orderID`,`line`,`ticketID`),
  KEY `fk_cgate_detail_orders_tickets_cgate_orders1_idx` (`orderID`),
  KEY `fk_cgate_detail_orders_tickets_cgate_tickets1_idx` (`ticketID`),
  KEY `seat_number` (`seat_number`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `cgate_detail_orders_tickets`
--

INSERT INTO `cgate_detail_orders_tickets` (`orderID`, `ticketID`, `line`, `issue_date`, `seat_number`, `item_number`, `item_vol`, `time_departure`, `date_departure`, `hotel_name`, `room_number`, `quantity_client`, `quantity_purchased`, `item_cost_price`, `item_unit_price`, `discount_percent`, `description`) VALUES
(1, 1, 1, '2013-12-26 03:43:37', '1a', NULL, NULL, '10:43:50', '0000-00-00', NULL, '11A', 2, NULL, NULL, NULL, NULL, NULL),
(134, 4, 1, NULL, '', NULL, NULL, '00:00:00', '0000-00-00', NULL, NULL, 0, '1', '15', '20', '0', NULL),
(213, 43, 1, '2014-01-21 04:57:53', '', NULL, NULL, '00:00:00', '0000-00-00', '', '', 0, '1', '12', '11', '0', ''),
(214, 36, 1, '2014-01-23 01:15:16', '', NULL, NULL, '00:00:00', '0000-00-00', '', '', 0, '1', '10', '10', '50', ''),
(215, 22, 1, '2014-01-23 03:09:42', '', NULL, NULL, '00:00:00', '0000-00-00', '', '', 0, '1', '10', '13', '0', NULL),
(217, 16, 1, '2014-01-23 03:25:35', '', NULL, NULL, '00:00:00', '0000-00-00', '', '', 0, '1', '10', '15', '5', ''),
(218, 4, 1, '2014-01-23 03:32:36', '', NULL, NULL, '00:00:01', '0000-00-00', '', '', 0, '1', '15', '20', '10', ''),
(219, 23, 1, '2014-01-23 03:37:36', '', NULL, NULL, '00:00:00', '0000-00-00', '', '', 0, '1', '10', '12', '3', ''),
(220, 33, 1, '2014-01-23 09:57:28', '1', NULL, NULL, '00:00:00', '0000-00-00', '', '', 0, '1', '10', '10', '0', ''),
(220, 32, 2, '2014-01-23 09:57:28', '2', NULL, NULL, '00:00:00', '0000-00-00', '', '', 0, '1', '10', '10', '0', ''),
(220, 20, 3, '2014-01-23 09:57:28', '3', NULL, NULL, '00:00:00', '0000-00-00', '', '', 0, '1', '10', '11', '0', ''),
(221, 30, 1, '2014-01-23 15:31:25', '1', NULL, NULL, '00:00:00', '0000-00-00', 'BB Hotel', '010A', 0, '1', '10', '10', '10', ''),
(222, 35, 1, '2014-01-24 01:16:58', '', NULL, NULL, '00:00:00', '0000-00-00', 'BB Hotel', '010A', 0, '1', '10', '9', '10', ''),
(223, 31, 1, '2014-01-25 06:50:39', '', NULL, NULL, '02:30:00', '0000-00-00', 'BB Hotel', '010A', 0, '1', '10', '10', '10', ''),
(224, 33, 1, '2014-01-25 15:39:59', '1', NULL, NULL, '13:30:00', '0000-00-00', 'AA Hotel', '012A', 0, '1', '10', '10', '5', ''),
(225, 32, 1, '2014-01-25 16:01:35', '1', NULL, NULL, '09:30:00', '0000-00-00', 'AA Hotel', '012A', 0, '1', '10', '10', '5', ''),
(226, 35, 1, '2014-01-25 16:13:40', '1', NULL, NULL, '13:30:00', '0000-00-00', 'AA Hotel', '012A', 0, '1', '10', '9', '5', ''),
(227, 43, 1, '2014-01-28 07:26:52', '3', NULL, NULL, '07:04:00', '0000-00-00', 'AA Hotel', '012A', 0, '1', '12', '11', '10', ''),
(227, 34, 2, '2014-01-28 07:26:52', '1', NULL, NULL, '07:04:00', '0000-00-00', 'AA Hotel', '012A', 0, '1', '9', '8', '20', ''),
(235, 43, 1, '2014-01-31 03:06:56', '', NULL, NULL, '00:00:00', '0000-00-00', '', '', 0, '1', '12', '11', '0', NULL),
(258, 35, 1, '2014-02-07 03:36:56', '3', NULL, NULL, '09:00:00', '0000-00-00', NULL, NULL, 0, '1', '10', '9', '0', NULL),
(259, 40, 1, '2014-02-04 06:39:22', '', NULL, NULL, '00:00:00', '0000-00-00', '', '', 0, '1', '10', '5', '0', NULL),
(301, 38, 1, '2014-02-05 03:57:39', '', NULL, NULL, '00:00:00', '0000-00-00', '', '', 0, '1', '10', '8', '0', NULL),
(311, 39, 1, '2014-02-05 06:50:18', '', NULL, NULL, '00:00:00', '0000-00-00', '', '', 0, '1', '10', '7', '0', NULL),
(313, 43, 1, '2014-02-05 07:06:06', '', NULL, NULL, '00:00:00', '0000-00-00', '', '', 0, '1', '12', '11', '0', NULL),
(317, 39, 1, '2014-02-06 07:59:49', '5', NULL, NULL, '10:00:00', '0000-00-00', '', '', 0, '1', '10', '7', '0', ''),
(317, 37, 2, '2014-02-06 07:59:50', '4', NULL, NULL, '10:00:00', '0000-00-00', '', '', 0, '1', '10', '7', '0', ''),
(318, 40, 1, '2014-02-06 08:14:31', '3', NULL, NULL, '00:00:00', '0000-00-00', '', '', 0, '1', '10', '5', '0', ''),
(318, 35, 2, '2014-02-06 08:14:31', '3', NULL, NULL, '00:00:00', '0000-00-00', '', '', 0, '1', '10', '9', '0', ''),
(319, 38, 1, '2014-02-07 04:19:31', '6-b', NULL, NULL, '00:00:00', '0000-00-00', 'AA Hotel', '012A', 0, '1', '10', '8', '0', NULL),
(319, 44, 2, '2014-02-07 04:19:31', '6-a', NULL, NULL, '00:00:00', '0000-00-00', 'AA Hotel', '012A', 0, '1', '10', '8', '0', NULL),
(328, 1, 1, '2014-02-07 17:56:10', NULL, NULL, NULL, '00:00:00', '0000-00-00', '', '', 0, '1', '10', '15', '0', NULL),
(329, 1, 1, '2014-02-07 17:56:48', NULL, NULL, NULL, '00:00:00', '0000-00-00', '', '', 0, '1', '10', '15', '0', NULL),
(330, 42, 1, '2014-02-07 17:59:52', NULL, NULL, NULL, '00:00:00', '0000-00-00', '', '', 0, '1', '10', '8', '0', NULL),
(330, 41, 2, '2014-02-07 17:59:52', NULL, NULL, NULL, '00:00:00', '0000-00-00', '', '', 0, '1', '10', '9', '0', NULL),
(340, 39, 1, '2014-02-09 16:58:52', '22', '0000ec', NULL, '00:00:00', '0000-00-00', '', '', 0, '1', '10', '7', '0', ''),
(340, 42, 2, '2014-02-09 16:58:52', '4', '000000e', NULL, '00:00:00', '0000-00-00', '', '', 0, '1', '10', '8', '0', ''),
(341, 51, 1, '2014-02-10 04:11:10', NULL, '000000ce', NULL, '00:00:00', '0000-00-00', '', '', 0, '1', '35', '26', '0', NULL),
(342, 51, 1, '2014-02-12 02:42:06', '3', '12222', NULL, '00:00:00', '0000-00-00', '', '', 0, '1', '135', '126', '0', NULL),
(342, 47, 2, '2014-02-12 02:42:06', '2', '23455', NULL, '00:00:00', '0000-00-00', '', '', 0, '1', '10', '10', '0', NULL),
(344, 51, 1, '2014-02-24 07:27:04', '12', '000002e', '4', '00:00:00', '0000-00-00', '', '', 0, '1', '135', '126', '0', NULL),
(344, 47, 2, '2014-02-24 07:27:04', '12', '000001e', '1', '00:00:00', '0000-00-00', '', '', 0, '1', '10', '10', '0', NULL),
(345, 47, 1, '2014-02-14 04:07:45', '4', '00000002', '2', '15:00:00', '0000-00-00', '', '', 0, '1', '10', '10', '0', ''),
(345, 51, 2, '2014-02-14 04:07:45', '3', '00000002', '2', '15:00:00', '0000-00-00', '', '', 0, '1', '135', '126', '0', ''),
(346, 50, 1, '2014-02-24 07:27:48', NULL, NULL, NULL, '00:00:00', '0000-00-00', '', '', 0, '1', '14', '11', '0', NULL),
(347, 50, 1, '2014-02-24 07:28:52', NULL, NULL, NULL, '00:00:00', '0000-00-00', '', '', 0, '1', '14', '11', '0', NULL),
(347, 51, 2, '2014-02-24 07:28:52', NULL, NULL, NULL, '00:00:00', '0000-00-00', '', '', 0, '1', '135', '126', '0', NULL),
(348, 50, 1, '2014-02-24 07:30:19', NULL, NULL, NULL, '00:00:00', '0000-00-00', '', '', 0, '1', '14', '11', '0', NULL),
(348, 51, 2, '2014-02-24 07:30:19', NULL, NULL, NULL, '00:00:00', '0000-00-00', '', '', 0, '1', '135', '126', '0', NULL),
(349, 48, 1, '2014-02-25 01:09:22', NULL, NULL, NULL, '00:00:00', '0000-00-00', '', '', 0, '1', '14', '11', '0', NULL),
(350, 48, 1, '2014-02-25 01:17:36', NULL, NULL, NULL, '00:00:00', '0000-00-00', '', '', 0, '1', '14', '11', '0', NULL),
(351, 49, 1, '2014-02-25 01:31:58', NULL, NULL, NULL, '00:00:00', '0000-00-00', '', '', 0, '1', '14', '12', '0', NULL),
(352, 46, 1, '2014-02-26 07:48:28', '', NULL, '', '09:00:00', '2014-02-28', '', '', 0, '1', '14', '15', '0', ''),
(352, 48, 2, '2014-02-26 07:48:28', '', NULL, '', '07:00:00', '2014-02-27', '', '', 0, '1', '14', '11', '0', ''),
(353, 51, 1, '2014-02-25 02:27:27', '', NULL, '', '13:00:00', '2014-02-26', '', '', 0, '1', '135', '126', '0', NULL),
(355, 48, 1, '2014-03-07 14:07:55', NULL, NULL, NULL, '00:00:00', '0000-00-00', '', '', 0, '1', '14', '11', '0', NULL),
(356, 51, 1, '2014-03-07 10:14:45', '1', '123', '1', '09:00:00', '2014-03-07', 'AA Hotel', '012A', 0, '1', '135', '126', '0', ''),
(356, 47, 2, '2014-03-07 10:14:45', '2', '12', '1', '09:00:00', '2014-03-08', 'AA Hotel', '012A', 0, '1', '10', '10', '0', ''),
(357, 51, 1, '2014-03-07 10:20:58', '1', '123', '1', '09:00:00', '2014-03-07', 'AA Hotel', '012A', 0, '1', '135', '126', '0', NULL),
(357, 47, 2, '2014-03-07 10:20:58', '2', '12', '1', '09:00:00', '2014-03-08', 'AA Hotel', '012A', 0, '1', '10', '10', '0', NULL),
(358, 51, 1, '2014-03-15 19:00:41', '1', '123', '1', '09:00:00', '2014-03-07', 'AA Hotel', '012A', 0, '1', '135', '126', '0', NULL),
(358, 47, 2, '2014-03-15 19:00:42', '2', '12', '1', '09:00:00', '2014-03-08', 'AA Hotel', '012A', 0, '1', '10', '10', '0', NULL),
(359, 51, 1, '2014-03-13 03:39:41', NULL, NULL, NULL, '00:00:00', '0000-00-00', '', '', 0, '1', '135', '126', '0', NULL),
(360, 47, 1, '2014-03-11 08:47:27', NULL, NULL, NULL, '00:00:00', '0000-00-00', '', '', 0, '1', '10', '10', '0', NULL),
(361, 54, 1, '2014-03-13 03:36:13', '11', NULL, '', '13:36:00', '2014-03-13', '', '', 0, '1', '12', '14', '0', ''),
(361, 50, 2, '2014-03-13 03:36:13', '12', '009977', '', '09:35:00', '2014-03-03', '', '', 0, '1', '14', '11', '0', ''),
(364, 48, 1, '2014-03-15 16:55:15', NULL, NULL, NULL, '00:00:00', '0000-00-00', '', '', 0, '1', '14', '11', '0', NULL),
(365, 48, 1, '2014-03-15 16:56:11', NULL, NULL, NULL, '00:00:00', '0000-00-00', '', '', 0, '1', '14', '11', '0', NULL),
(366, 49, 1, '2014-03-15 16:59:20', NULL, NULL, NULL, '00:00:00', '0000-00-00', '', '', 0, '1', '14', '12', '0', NULL),
(367, 48, 1, '2014-03-15 17:50:10', NULL, NULL, NULL, '00:00:00', '0000-00-00', '', '', 0, '1', '14', '11', '0', NULL),
(368, 48, 1, '2014-03-15 18:14:29', NULL, NULL, NULL, '00:00:00', '0000-00-00', '', '', 0, '1', '14', '11', '0', NULL),
(369, 51, 1, '2014-03-15 21:03:42', NULL, NULL, NULL, '00:00:00', '0000-00-00', '', '', 0, '1', '135', '126', '0', NULL),
(370, 51, 1, '2014-03-15 21:06:17', NULL, NULL, NULL, '00:00:00', '0000-00-00', '', '', 0, '1', '135', '126', '0', NULL),
(371, 46, 1, '2014-03-16 07:04:10', '', NULL, '', '00:00:00', '0000-00-00', '', '', 0, '2', '12', '15', '0', '');

-- --------------------------------------------------------

--
-- Table structure for table `cgate_detail_orders_tours`
--

CREATE TABLE IF NOT EXISTS `cgate_detail_orders_tours` (
  `tour_id` int(10) NOT NULL,
  `orderID` int(10) NOT NULL,
  `issue_date` timestamp NULL DEFAULT NULL,
  `departure_date` date DEFAULT NULL,
  `departure_time` time DEFAULT NULL,
  `line` int(3) NOT NULL DEFAULT '0',
  `room_number` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `hotel_name` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `quantity_client` int(10) NOT NULL,
  `deposit` float(4,2) DEFAULT NULL,
  `balance` float(4,2) DEFAULT NULL,
  `by` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'How to travel of tour, by a bus, tuk tuk',
  `destination` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `quantity_purchased` decimal(10,0) DEFAULT NULL,
  `item_cost_price` decimal(10,0) DEFAULT NULL,
  `item_unit_price` decimal(10,0) DEFAULT NULL,
  `discount_percent` decimal(10,0) NOT NULL DEFAULT '0',
  `description` varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`tour_id`,`orderID`,`line`),
  KEY `fk_cgate_detail_orders_tickets_cgate_orders1_idx` (`orderID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `cgate_detail_orders_tours`
--

INSERT INTO `cgate_detail_orders_tours` (`tour_id`, `orderID`, `issue_date`, `departure_date`, `departure_time`, `line`, `room_number`, `hotel_name`, `quantity_client`, `deposit`, `balance`, `by`, `destination`, `quantity_purchased`, `item_cost_price`, `item_unit_price`, `discount_percent`, `description`) VALUES
(1, 244, '2014-01-31 04:14:44', NULL, '00:00:00', 1, '', '', 0, NULL, NULL, NULL, 'destination', '1', '50', '45', '0', ''),
(1, 247, '2014-02-01 01:21:00', '0000-00-00', '14:00:00', 1, NULL, NULL, 0, NULL, NULL, '', 'PP - Kampot', '1', '50', '45', '0', ''),
(1, 248, '2014-02-01 01:39:43', '0000-00-00', '15:00:00', 1, '', '', 0, NULL, NULL, '', 'PP - Kampot', '1', '50', '45', '0', ''),
(1, 249, '2014-02-01 01:41:04', '0000-00-00', '15:00:00', 1, '', '', 0, NULL, NULL, '', 'PP - Kampot', '1', '50', '45', '0', ''),
(4, 252, '2014-02-01 03:05:13', '0000-00-00', '00:00:00', 1, '', '', 0, NULL, NULL, '', 'PP - Takeo', '1', '14', '15', '0', 'This is the testing of tour record'),
(5, 253, '2014-02-05 18:15:12', '0000-00-00', '00:00:00', 1, NULL, NULL, 0, NULL, NULL, '', 'PP - Takeo', '1', '14', '15', '0', 'This is the testing of tour record'),
(6, 251, '2014-02-01 03:03:34', '0000-00-00', '00:00:00', 1, '010A', 'BB Hotel', 0, NULL, NULL, '', 'PP - Takeo', '1', '20', '25', '0', 'Hello everybody'),
(7, 256, '2014-02-03 03:16:22', '0000-00-00', '00:00:00', 1, '', '', 0, NULL, NULL, '', 'PP - BB', '1', '35', '40', '0', 'This is the way in business'),
(8, 343, '2014-02-12 03:12:45', '0000-00-00', '00:00:00', 2, '', '', 0, NULL, NULL, '', 'PP - BB', '1', '35', '35', '0', 'Hello world'),
(8, 362, '2014-03-14 18:36:19', '0000-00-00', '00:00:00', 1, '', '', 0, NULL, NULL, '', 'PP - BB', '1', '35', '35', '0', 'Hello world'),
(9, 254, '2014-02-03 01:59:13', '0000-00-00', '00:00:00', 1, '', '', 0, NULL, NULL, '', 'PP - Takeo', '1', '20', '26', '0', 'Hello Hinghorng company'),
(9, 255, '2014-02-03 03:04:04', '0000-00-00', '00:00:00', 1, '', '', 0, NULL, NULL, '', 'PP - Takeo', '1', '20', '26', '0', 'Hello Hinghorng company'),
(9, 331, '2014-02-07 18:20:51', '0000-00-00', '00:00:00', 1, '', '', 0, NULL, NULL, '', 'PP - Takeo', '1', '20', '26', '0', 'Hello Hinghorng company'),
(9, 343, '2014-02-12 03:12:45', '0000-00-00', '00:00:00', 1, '', '', 0, NULL, NULL, '', 'PP - Takeo', '1', '20', '26', '0', 'Hello Hinghorng company'),
(14, 245, '2014-01-31 09:09:16', '2014-02-14', '00:00:00', 1, NULL, NULL, 0, NULL, NULL, 'bus', 'PP - Takeo', '1', '10', '12', '0', 'testing tour takeo province'),
(14, 246, '2014-01-31 09:21:21', '2014-02-14', '14:00:00', 1, '', '', 0, NULL, NULL, 'bus', 'PP - Takeo', '1', '10', '12', '0', 'testing tour takeo province'),
(14, 250, '2014-02-01 02:29:31', '2014-02-14', '00:00:00', 1, '', '', 0, NULL, NULL, 'bus', 'PP - Takeo', '1', '10', '12', '0', 'testing tour takeo province'),
(14, 314, '2014-02-05 07:07:38', '2014-02-14', '00:00:00', 1, '', '', 0, NULL, NULL, 'bus', 'PP - Takeo', '1', '10', '12', '0', 'testing tour takeo province'),
(14, 315, '2014-02-05 07:48:52', '2014-02-14', '00:00:00', 1, '', '', 0, NULL, NULL, 'bus', 'PP - Takeo', '1', '10', '12', '0', 'testing tour takeo province'),
(15, 257, '2014-02-03 06:58:02', '2014-03-08', '09:00:00', 1, '', '', 0, NULL, NULL, 'bus', 'PP - Takeo', '1', '25', '30', '0', 'Chhing House'),
(15, 316, '2014-02-05 07:58:04', '2014-03-08', '09:00:00', 1, '', '', 0, NULL, NULL, 'bus', 'PP - Takeo', '1', '25', '30', '0', 'Chhing House');

-- --------------------------------------------------------

--
-- Table structure for table `cgate_detail_orders_visas`
--

CREATE TABLE IF NOT EXISTS `cgate_detail_orders_visas` (
  `orderID` int(10) NOT NULL DEFAULT '0',
  `item_visa_typeID` int(10) NOT NULL DEFAULT '0',
  `issue_date` timestamp NULL DEFAULT NULL,
  `valid` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `apply_date` date NOT NULL,
  `line` int(3) NOT NULL DEFAULT '0',
  `pickup_date` date NOT NULL,
  `number_of_day` int(10) NOT NULL,
  `actual_price` decimal(15,2) NOT NULL DEFAULT '0.00',
  `sell_price` decimal(15,2) DEFAULT NULL,
  `deposit` decimal(15,2) DEFAULT NULL,
  `balance` decimal(15,2) DEFAULT NULL,
  `room_number` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `passportID` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `invoice_code` varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL,
  `commision_price` decimal(15,2) DEFAULT NULL,
  `commisioner` int(11) DEFAULT NULL,
  `status_visa` enum('Picked','Send','Not received') COLLATE utf8_unicode_ci NOT NULL,
  `received_from_employee` int(11) NOT NULL,
  `arrival_date` date DEFAULT NULL,
  `discount_percent` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`line`),
  KEY `fk_detail_orders_visas_visas_types1_idx` (`item_visa_typeID`),
  KEY `fk_cgate_detail_orders_visas_cgate_orders1_idx` (`orderID`),
  KEY `fk_cgate_detail_orders_visas_cgate_employees1_idx` (`received_from_employee`),
  KEY `fk_cgate_detail_orders_visas_cgate_suppliers1_idx` (`commisioner`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cgate_detial_orders_massages`
--

CREATE TABLE IF NOT EXISTS `cgate_detial_orders_massages` (
  `id_order_massage` int(11) NOT NULL DEFAULT '0',
  `item_massage_id` int(11) NOT NULL DEFAULT '0',
  `line` int(11) NOT NULL DEFAULT '0',
  `issue_date` datetime DEFAULT NULL,
  `time_in` time DEFAULT NULL,
  `time_out` time DEFAULT NULL,
  `massage_name` varchar(150) DEFAULT NULL,
  `unit_price` decimal(10,0) DEFAULT NULL,
  `sale_price` decimal(10,0) DEFAULT NULL,
  `quantity_purchased` decimal(10,0) DEFAULT NULL,
  `item_cost_price` decimal(10,0) DEFAULT NULL,
  `discount_percent` decimal(10,0) DEFAULT NULL,
  `deposite` decimal(10,0) DEFAULT NULL,
  PRIMARY KEY (`id_order_massage`,`item_massage_id`,`line`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `cgate_detial_orders_massages`
--

INSERT INTO `cgate_detial_orders_massages` (`id_order_massage`, `item_massage_id`, `line`, `issue_date`, `time_in`, `time_out`, `massage_name`, `unit_price`, `sale_price`, `quantity_purchased`, `item_cost_price`, `discount_percent`, `deposite`) VALUES
(338, 3, 1, '2014-02-08 20:43:04', '09:00:00', '12:00:00', 'massage1', '43', NULL, NULL, NULL, NULL, NULL),
(339, 3, 1, '2014-02-09 00:00:19', '09:00:00', '12:00:00', 'massage1', '43', NULL, NULL, NULL, NULL, NULL),
(354, 7, 1, '2014-03-06 11:19:37', '11:19:00', '00:00:00', 'Massage body with oil', NULL, '12', '1', NULL, '0', NULL),
(372, 7, 1, '2014-03-17 08:20:07', '08:19:00', '00:00:00', 'Massage body with oil', '0', '15', '1', NULL, '0', NULL),
(373, 7, 1, '2014-03-17 08:23:19', '08:19:00', '00:00:00', 'Massage body with oil', '0', '15', '1', NULL, '0', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `cgate_employees`
--

CREATE TABLE IF NOT EXISTS `cgate_employees` (
  `username` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `deleted` int(1) NOT NULL DEFAULT '0',
  `position_id` int(10) DEFAULT NULL,
  `employee_id` int(10) DEFAULT NULL,
  UNIQUE KEY `username` (`username`),
  KEY `deleted` (`deleted`),
  KEY `phppos_employees_ibfk_1_idx` (`employee_id`),
  KEY `fk_cgate_employees_cgate_positions1_idx` (`position_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `cgate_employees`
--

INSERT INTO `cgate_employees` (`username`, `password`, `deleted`, `position_id`, `employee_id`) VALUES
('admin', '5f4dcc3b5aa765d61d8327deb882cf99', 0, 1, 1),
('sreychen', '25f9e794323b453885f5181f1b624d0b', 0, 2, 2),
('yulyhong', '25f9e794323b453885f5181f1b624d0b', 0, 2, 3),
('lovely.lucky', '343d9040a671c45832ee5381860e2996', 0, 2, 214),
(NULL, '202cb962ac59075b964b07152d234b70', 1, 2, 217),
('channa', '202cb962ac59075b964b07152d234b70', 0, NULL, 218),
('', '202cb962ac59075b964b07152d234b70', 0, 2, 219),
(NULL, '202cb962ac59075b964b07152d234b70', 1, NULL, 220),
(NULL, '202cb962ac59075b964b07152d234b70', 1, NULL, 221),
('lylylong', '202cb962ac59075b964b07152d234b70', 0, 1, 222),
('hunnang', '202cb962ac59075b964b07152d234b70', 0, 2, 223),
(NULL, 'c4ca4238a0b923820dcc509a6f75849b', 1, 2, 224),
('zcenh', '202cb962ac59075b964b07152d234b70', 0, 2, 225),
('hello', '202cb962ac59075b964b07152d234b70', 0, 2, 233),
('tt', '25d55ad283aa400af464c76d713c07ad', 0, 1, 235),
('channa.hung', '25d55ad283aa400af464c76d713c07ad', 0, 2, 236),
('saorin', '25d55ad283aa400af464c76d713c07ad', 0, 2, 240);

-- --------------------------------------------------------

--
-- Table structure for table `cgate_exchange_money`
--

CREATE TABLE IF NOT EXISTS `cgate_exchange_money` (
  `exchange_id` int(10) NOT NULL AUTO_INCREMENT,
  `issue_date` timestamp NULL DEFAULT NULL,
  `quantity_exchange` decimal(15,2) DEFAULT NULL,
  `amount_exchange` decimal(15,2) DEFAULT NULL,
  `time_exchange` timestamp NULL DEFAULT NULL,
  `code_of_money` varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL,
  `received_from` int(10) DEFAULT NULL COMMENT 'Received from office',
  `employeeID` int(10) DEFAULT NULL,
  `currency_typeID` int(10) DEFAULT NULL,
  `value_currency` decimal(15,2) DEFAULT NULL,
  PRIMARY KEY (`exchange_id`),
  KEY `fk_exchange_money_currency_types1_idx` (`currency_typeID`),
  KEY `fk_exchange_money_employees1_idx` (`employeeID`),
  KEY `fk_cgate_exchange_money_cgate_offices1_idx` (`received_from`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cgate_giftcards`
--

CREATE TABLE IF NOT EXISTS `cgate_giftcards` (
  `giftcard_id` int(11) NOT NULL AUTO_INCREMENT,
  `giftcard_number` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `value` decimal(15,2) NOT NULL,
  `customerID` int(10) DEFAULT NULL,
  `deleted` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`giftcard_id`),
  UNIQUE KEY `giftcard_number` (`giftcard_number`),
  KEY `deleted` (`deleted`),
  KEY `phppos_giftcards_ibfk_1` (`customerID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cgate_guides`
--

CREATE TABLE IF NOT EXISTS `cgate_guides` (
  `guide_id` int(10) NOT NULL AUTO_INCREMENT,
  `guide_fname` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `guide_lname` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `deleted` int(1) NOT NULL DEFAULT '0',
  `gender` enum('Female','Male') COLLATE utf8_unicode_ci DEFAULT NULL,
  `tel` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `guide_type` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`guide_id`),
  KEY `person_id` (`guide_id`),
  KEY `deleted` (`deleted`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=8 ;

--
-- Dumping data for table `cgate_guides`
--

INSERT INTO `cgate_guides` (`guide_id`, `guide_fname`, `guide_lname`, `deleted`, `gender`, `tel`, `email`, `guide_type`) VALUES
(1, 'Hello', 'hi', 0, 'Male', '092123432', 'hello@gmial.ocm', 'Chinese'),
(2, 'Fguide', 'Lguide', 0, 'Male', '090461710', 'Lguide@gmail.com', 'Tour'),
(3, 'hi guide', 'gude', 0, 'Female', '0972792214', 'higuide@gmail.com', 'Tour'),
(4, 'Ly Iguide', 'Uguide', 0, 'Female', '012343434', 'iguide@gmail.com', 'Tour'),
(5, 'zcehna', 'zhen', 0, 'Female', '092123437', 'zcehna@gmail.com', 'Chinese'),
(6, 'new', 'guide', 0, 'Female', '098989887', 'new@gmail.com', 'Vitnames'),
(7, 'new1', 'guide1', 0, 'Female', '092123432', '', 'Brazil');

-- --------------------------------------------------------

--
-- Table structure for table `cgate_inventory`
--

CREATE TABLE IF NOT EXISTS `cgate_inventory` (
  `trans_id` int(11) NOT NULL AUTO_INCREMENT,
  `trans_items` int(11) DEFAULT '0',
  `trans_user` int(11) NOT NULL DEFAULT '0',
  `trans_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `trans_comment` text COLLATE utf8_unicode_ci NOT NULL,
  `trans_inventory` decimal(15,2) NOT NULL DEFAULT '0.00',
  `type_items` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`trans_id`),
  KEY `phppos_inventory_ibfk_2` (`trans_user`),
  KEY `fk_phppos_inventory_currency_types1_idx` (`trans_items`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=826 ;

--
-- Dumping data for table `cgate_inventory`
--

INSERT INTO `cgate_inventory` (`trans_id`, `trans_items`, `trans_user`, `trans_date`, `trans_comment`, `trans_inventory`, `type_items`) VALUES
(1, NULL, 1, '2014-01-08 08:06:21', 'CGATE 129', '-1.00', ''),
(2, NULL, 1, '2014-01-08 08:06:21', 'CGATE 129', '-1.00', ''),
(3, NULL, 1, '2014-01-08 08:15:53', 'CGATE 130', '-1.00', ''),
(4, NULL, 1, '2014-01-08 08:15:53', 'CGATE 130', '-1.00', ''),
(8, NULL, 1, '2014-01-09 01:32:56', 'CGATE 134', '-1.00', ''),
(9, NULL, 1, '2014-01-09 04:03:45', 'CGATE 135', '-1.00', ''),
(10, NULL, 1, '2014-01-09 04:03:46', 'CGATE 135', '-1.00', ''),
(11, NULL, 1, '2014-01-09 04:03:46', 'CGATE 135', '-1.00', ''),
(12, NULL, 1, '2014-01-13 03:07:35', 'CGATE 136', '-1.00', ''),
(13, NULL, 1, '2014-01-13 03:07:35', 'CGATE 136', '-1.00', ''),
(14, NULL, 1, '2014-01-13 03:09:52', 'CGATE 137', '-1.00', ''),
(15, NULL, 1, '2014-01-13 03:09:52', 'CGATE 137', '-1.00', ''),
(16, NULL, 1, '2014-01-13 03:11:55', 'CGATE 138', '-1.00', ''),
(17, NULL, 1, '2014-01-13 03:11:55', 'CGATE 138', '-1.00', ''),
(18, NULL, 1, '2014-01-13 03:12:56', 'CGATE 139', '-1.00', ''),
(19, NULL, 1, '2014-01-13 03:12:56', 'CGATE 139', '-1.00', ''),
(20, NULL, 1, '2014-01-13 03:14:32', 'CGATE 140', '-1.00', ''),
(21, NULL, 1, '2014-01-13 03:14:32', 'CGATE 140', '-1.00', ''),
(22, NULL, 1, '2014-01-13 03:16:42', 'CGATE 141', '-1.00', ''),
(23, NULL, 1, '2014-01-13 03:16:42', 'CGATE 141', '-1.00', ''),
(24, NULL, 1, '2014-01-13 03:20:11', 'CGATE 142', '-1.00', ''),
(25, NULL, 1, '2014-01-13 03:26:07', 'CGATE 143', '-1.00', ''),
(26, NULL, 1, '2014-01-13 03:28:32', 'CGATE 144', '-1.00', ''),
(27, NULL, 1, '2014-01-13 03:30:07', 'CGATE 145', '-1.00', ''),
(28, NULL, 1, '2014-01-13 03:36:17', 'CGATE 146', '-1.00', ''),
(29, NULL, 1, '2014-01-13 03:36:17', 'CGATE 146', '-1.00', ''),
(30, NULL, 1, '2014-01-13 03:40:29', 'CGATE 147', '-1.00', ''),
(31, NULL, 1, '2014-01-13 03:43:08', 'CGATE 148', '-1.00', ''),
(32, NULL, 1, '2014-01-13 03:46:59', 'CGATE 149', '-1.00', ''),
(33, NULL, 1, '2014-01-13 03:49:46', 'CGATE 150', '-1.00', ''),
(34, NULL, 1, '2014-01-13 04:05:29', 'CGATE 151', '-1.00', ''),
(35, NULL, 1, '2014-01-14 01:20:14', 'CGATE 152', '-1.00', ''),
(36, NULL, 1, '2014-01-14 06:55:15', 'CGATE 153', '-1.00', ''),
(62, NULL, 1, '2014-01-17 05:38:43', 'CGATE 142', '1.00', ''),
(64, NULL, 1, '2014-01-17 05:42:20', 'CGATE 142', '1.00', ''),
(66, NULL, 1, '2014-01-17 05:44:22', 'CGATE 142', '1.00', ''),
(68, NULL, 1, '2014-01-17 05:44:51', 'CGATE 142', '1.00', ''),
(70, NULL, 1, '2014-01-17 05:48:11', 'CGATE 142', '1.00', ''),
(72, NULL, 1, '2014-01-17 05:50:34', 'CGATE 142', '1.00', ''),
(74, NULL, 1, '2014-01-17 05:51:01', 'CGATE 142', '1.00', ''),
(76, NULL, 1, '2014-01-17 05:52:34', 'CGATE 142', '1.00', ''),
(78, NULL, 1, '2014-01-17 05:54:32', 'CGATE 142', '1.00', ''),
(80, NULL, 1, '2014-01-17 06:11:18', 'CGATE 142', '1.00', ''),
(82, NULL, 1, '2014-01-17 06:12:49', 'CGATE 142', '1.00', ''),
(84, NULL, 1, '2014-01-17 06:16:11', 'CGATE 142', '1.00', ''),
(86, NULL, 1, '2014-01-17 06:16:55', 'CGATE 142', '1.00', ''),
(87, NULL, 1, '2014-01-17 06:18:18', 'CGATE 142', '1.00', ''),
(89, NULL, 1, '2014-01-17 06:19:22', 'CGATE 142', '1.00', ''),
(91, NULL, 1, '2014-01-17 06:19:58', 'CGATE 142', '1.00', ''),
(93, NULL, 1, '2014-01-17 06:21:01', 'CGATE 142', '1.00', ''),
(95, NULL, 1, '2014-01-17 08:10:26', 'CGATE 142', '1.00', ''),
(97, NULL, 1, '2014-01-17 08:15:19', 'CGATE 142', '1.00', ''),
(99, NULL, 1, '2014-01-17 08:16:21', 'CGATE 142', '1.00', ''),
(101, NULL, 1, '2014-01-17 08:16:36', 'CGATE 142', '1.00', ''),
(103, NULL, 1, '2014-01-17 08:16:43', 'CGATE 142', '1.00', ''),
(105, NULL, 1, '2014-01-17 08:19:11', 'CGATE 142', '1.00', ''),
(107, NULL, 1, '2014-01-17 08:20:41', 'CGATE 142', '1.00', ''),
(109, NULL, 1, '2014-01-17 08:21:20', 'CGATE 142', '1.00', ''),
(111, NULL, 1, '2014-01-17 08:23:08', 'CGATE 142', '1.00', ''),
(113, NULL, 1, '2014-01-17 08:23:46', 'CGATE 142', '1.00', ''),
(125, NULL, 1, '2014-01-17 10:05:24', 'CGATE 143', '1.00', ''),
(127, NULL, 1, '2014-01-17 10:08:17', 'CGATE 143', '1.00', ''),
(130, NULL, 1, '2014-01-17 10:20:50', 'CGATE 145', '1.00', ''),
(132, NULL, 1, '2014-01-17 10:22:22', 'CGATE 145', '1.00', ''),
(134, NULL, 1, '2014-01-18 01:29:13', 'CGATE 146', '1.00', ''),
(136, NULL, 1, '2014-01-18 01:29:32', 'CGATE 146', '1.00', ''),
(138, NULL, 1, '2014-01-18 01:29:56', 'CGATE 146', '1.00', ''),
(140, NULL, 1, '2014-01-18 01:30:22', 'CGATE 146', '1.00', ''),
(142, NULL, 1, '2014-01-18 01:30:54', 'CGATE 146', '1.00', ''),
(144, NULL, 1, '2014-01-18 01:31:20', 'CGATE 146', '1.00', ''),
(148, NULL, 1, '2014-01-18 02:13:18', 'CGATE 147', '1.00', ''),
(150, NULL, 1, '2014-01-18 02:16:14', 'CGATE 147', '1.00', ''),
(152, NULL, 1, '2014-01-18 02:16:47', 'CGATE 147', '1.00', ''),
(154, NULL, 1, '2014-01-18 02:20:22', 'CGATE 147', '1.00', ''),
(156, NULL, 1, '2014-01-18 02:21:38', 'CGATE 147', '1.00', ''),
(158, NULL, 1, '2014-01-18 02:27:11', 'CGATE 147', '1.00', ''),
(169, NULL, 1, '2014-01-18 03:07:24', 'CGATE 187', '-1.00', ''),
(170, NULL, 1, '2014-01-18 03:09:04', 'CGATE 188', '-1.00', ''),
(177, NULL, 1, '2014-01-18 04:00:13', 'CGATE 195', '-1.00', ''),
(178, NULL, 1, '2014-01-18 04:06:53', 'CGATE 196', '-1.00', ''),
(180, NULL, 1, '2014-01-18 04:07:19', 'CGATE 196', '1.00', ''),
(182, NULL, 1, '2014-01-18 04:08:06', 'CGATE 196', '1.00', ''),
(184, NULL, 1, '2014-01-18 04:33:43', 'CGATE 196', '1.00', ''),
(186, NULL, 1, '2014-01-18 04:34:12', 'CGATE 196', '1.00', ''),
(188, NULL, 1, '2014-01-18 04:34:42', 'CGATE 196', '1.00', ''),
(190, NULL, 1, '2014-01-18 04:37:08', 'CGATE 196', '1.00', ''),
(191, NULL, 1, '2014-01-18 04:54:11', 'CGATE 197', '-1.00', ''),
(193, NULL, 1, '2014-01-18 04:59:24', 'CGATE 197', '1.00', ''),
(195, NULL, 1, '2014-01-18 04:59:53', 'CGATE 197', '1.00', ''),
(196, NULL, 1, '2014-01-18 05:02:47', 'CGATE 198', '-1.00', ''),
(198, NULL, 1, '2014-01-18 05:03:11', 'CGATE 198', '1.00', ''),
(200, NULL, 1, '2014-01-18 05:05:34', 'CGATE 198', '1.00', ''),
(202, NULL, 1, '2014-01-18 05:05:58', 'CGATE 198', '1.00', ''),
(203, NULL, 1, '2014-01-18 05:08:46', 'CGATE 199', '-1.00', ''),
(205, NULL, 1, '2014-01-18 05:09:20', 'CGATE 199', '1.00', ''),
(207, NULL, 1, '2014-01-18 05:10:10', 'CGATE 199', '1.00', ''),
(208, NULL, 1, '2014-01-18 05:13:44', 'CGATE 200', '-1.00', ''),
(209, NULL, 1, '2014-01-19 15:41:47', 'CGATE 201', '-2.00', ''),
(211, NULL, 1, '2014-01-19 15:42:56', 'CGATE 201', '-1.00', ''),
(212, NULL, 1, '2014-01-20 01:29:21', 'CGATE 202', '-1.00', ''),
(214, NULL, 1, '2014-01-20 02:04:02', 'CGATE 202', '-1.00', ''),
(216, NULL, 1, '2014-01-20 02:06:13', 'CGATE 202', '-1.00', ''),
(218, NULL, 1, '2014-01-20 02:11:13', 'CGATE 202', '-1.00', ''),
(220, NULL, 1, '2014-01-20 02:12:11', 'CGATE 202', '-1.00', ''),
(221, NULL, 1, '2014-01-20 02:16:11', 'CGATE 203', '-1.00', ''),
(223, NULL, 1, '2014-01-20 02:17:25', 'CGATE 203', '-1.00', ''),
(225, NULL, 1, '2014-01-20 02:18:20', 'CGATE 203', '-1.00', ''),
(226, NULL, 1, '2014-01-20 02:21:14', 'CGATE 204', '-1.00', ''),
(228, NULL, 1, '2014-01-20 02:28:17', 'CGATE 204', '-1.00', ''),
(230, NULL, 1, '2014-01-20 03:10:05', 'CGATE 204', '-1.00', ''),
(232, NULL, 1, '2014-01-20 03:10:37', 'CGATE 204', '-1.00', ''),
(234, NULL, 1, '2014-01-20 03:17:24', 'CGATE 204', '-1.00', ''),
(236, NULL, 1, '2014-01-20 03:18:59', 'CGATE 204', '-1.00', ''),
(238, NULL, 1, '2014-01-20 03:19:26', 'CGATE 204', '-1.00', ''),
(240, NULL, 1, '2014-01-20 03:19:51', 'CGATE 204', '-1.00', ''),
(242, NULL, 1, '2014-01-20 03:32:18', 'CGATE 204', '-1.00', ''),
(243, NULL, 1, '2014-01-20 03:32:19', 'CGATE 204', '-1.00', ''),
(244, NULL, 1, '2014-01-20 03:32:19', 'CGATE 204', '-1.00', ''),
(248, NULL, 1, '2014-01-20 03:34:22', 'CGATE 204', '-1.00', ''),
(249, NULL, 1, '2014-01-20 03:34:22', 'CGATE 204', '-1.00', ''),
(250, NULL, 1, '2014-01-20 03:34:22', 'CGATE 204', '-1.00', ''),
(254, NULL, 1, '2014-01-20 03:42:38', 'CGATE 204', '-1.00', ''),
(255, NULL, 1, '2014-01-20 03:42:39', 'CGATE 204', '-1.00', ''),
(256, NULL, 1, '2014-01-20 03:42:39', 'CGATE 204', '-1.00', ''),
(260, NULL, 1, '2014-01-20 03:43:38', 'CGATE 204', '-1.00', ''),
(261, NULL, 1, '2014-01-20 03:43:38', 'CGATE 204', '-1.00', ''),
(262, NULL, 1, '2014-01-20 03:43:38', 'CGATE 204', '-1.00', ''),
(266, NULL, 1, '2014-01-20 03:44:11', 'CGATE 204', '-1.00', ''),
(267, NULL, 1, '2014-01-20 03:44:11', 'CGATE 204', '-1.00', ''),
(268, NULL, 1, '2014-01-20 03:44:12', 'CGATE 204', '-1.00', ''),
(269, NULL, 1, '2014-01-20 03:48:08', 'CGATE 205', '-1.00', ''),
(271, NULL, 1, '2014-01-20 03:52:25', 'CGATE 205', '-1.00', ''),
(273, NULL, 1, '2014-01-20 03:52:50', 'CGATE 205', '-1.00', ''),
(275, NULL, 1, '2014-01-20 03:56:07', 'CGATE 205', '-1.00', ''),
(277, NULL, 1, '2014-01-20 04:06:13', 'CGATE 205', '-1.00', ''),
(279, NULL, 1, '2014-01-20 04:10:06', 'CGATE 205', '-1.00', ''),
(281, NULL, 1, '2014-01-20 04:15:07', 'CGATE 205', '-1.00', ''),
(283, NULL, 1, '2014-01-20 04:15:34', 'CGATE 205', '-1.00', ''),
(284, NULL, 1, '2014-01-20 04:22:03', 'CGATE 206', '-1.00', ''),
(286, NULL, 1, '2014-01-20 04:23:06', 'CGATE 206', '-1.00', ''),
(287, NULL, 1, '2014-01-20 04:34:55', 'CGATE 207', '-1.00', ''),
(289, NULL, 1, '2014-01-20 04:42:55', 'CGATE 207', '-1.00', ''),
(291, NULL, 1, '2014-01-20 04:45:32', 'CGATE 207', '-1.00', ''),
(292, NULL, 1, '2014-01-20 04:46:56', 'CGATE 208', '-1.00', ''),
(294, NULL, 1, '2014-01-20 04:47:52', 'CGATE 208', '-1.00', ''),
(296, NULL, 1, '2014-01-20 04:50:38', 'CGATE 208', '-1.00', ''),
(298, NULL, 1, '2014-01-20 04:53:37', 'CGATE 208', '-1.00', ''),
(300, NULL, 1, '2014-01-20 05:02:51', 'CGATE 208', '-1.00', ''),
(302, NULL, 1, '2014-01-20 05:37:22', 'CGATE 208', '-1.00', ''),
(303, NULL, 1, '2014-01-20 05:41:09', 'CGATE 209', '-1.00', ''),
(304, NULL, 1, '2014-01-20 05:45:59', 'CGATE 210', '-1.00', ''),
(305, NULL, 1, '2014-01-20 05:50:36', 'CGATE 211', '-1.00', ''),
(306, NULL, 1, '2014-01-20 05:52:45', 'CGATE 212', '-1.00', ''),
(308, NULL, 1, '2014-01-21 04:57:53', 'CGATE 213', '-1.00', ''),
(309, NULL, 1, '2014-01-23 01:15:16', 'CGATE 214', '-1.00', ''),
(310, NULL, 1, '2014-01-23 03:09:42', 'CGATE 215', '-1.00', ''),
(311, NULL, 1, '2014-01-23 03:20:20', 'CGATE 216', '-1.00', ''),
(312, NULL, 1, '2014-01-23 03:25:35', 'CGATE 217', '-1.00', ''),
(313, NULL, 1, '2014-01-23 03:32:37', 'CGATE 218', '-1.00', ''),
(314, NULL, 1, '2014-01-23 03:37:36', 'CGATE 219', '-1.00', ''),
(315, NULL, 1, '2014-01-23 09:57:28', 'CGATE 220', '-1.00', ''),
(316, NULL, 1, '2014-01-23 09:57:28', 'CGATE 220', '-1.00', ''),
(317, NULL, 1, '2014-01-23 09:57:29', 'CGATE 220', '-1.00', ''),
(318, NULL, 1, '2014-01-23 15:31:25', 'CGATE 221', '-1.00', ''),
(319, NULL, 1, '2014-01-24 01:16:58', 'CGATE 222', '-1.00', ''),
(320, NULL, 1, '2014-01-25 06:50:39', 'CGATE 223', '-1.00', ''),
(321, NULL, 1, '2014-01-25 15:40:00', 'CGATE 224', '-1.00', ''),
(322, NULL, 1, '2014-01-25 16:01:36', 'CGATE 225', '-1.00', ''),
(323, NULL, 1, '2014-01-25 16:13:41', 'CGATE 226', '-1.00', ''),
(324, NULL, 1, '2014-01-28 07:26:52', 'CGATE 227', '-1.00', ''),
(325, NULL, 1, '2014-01-28 07:26:52', 'CGATE 227', '-1.00', ''),
(349, 1, 1, '2014-01-31 04:14:44', 'CGATE 244', '-1.00', 'tours'),
(350, 14, 1, '2014-01-31 09:06:36', 'Manual Edit of Quantity', '0.00', 'tours'),
(351, 14, 1, '2014-01-31 09:09:16', 'CGATE 245', '-1.00', 'tours'),
(352, 14, 1, '2014-01-31 09:21:21', 'CGATE 246', '-1.00', 'tours'),
(353, 1, 1, '2014-02-01 01:21:00', 'CGATE 247', '-1.00', 'tours'),
(354, 1, 1, '2014-02-01 01:39:43', 'CGATE 248', '-1.00', 'tours'),
(355, 1, 1, '2014-02-01 01:41:04', 'CGATE 249', '-1.00', 'tours'),
(356, 14, 1, '2014-02-01 02:29:31', 'CGATE 250', '-1.00', 'tours'),
(357, 6, 1, '2014-02-01 03:03:34', 'CGATE 251', '-1.00', 'tours'),
(358, 4, 1, '2014-02-01 03:05:13', 'CGATE 252', '-1.00', 'tours'),
(359, 5, 1, '2014-02-01 03:10:50', 'CGATE 253', '-1.00', 'tours'),
(360, 5, 1, '2014-02-01 04:44:54', 'CGATE 253', '-1.00', 'tours'),
(361, 15, 1, '2014-02-01 05:24:33', 'Manual Edit of Quantity', '0.00', 'tours'),
(362, 5, 1, '2014-02-01 07:50:56', 'CGATE 253', '-1.00', 'tours'),
(363, 5, 1, '2014-02-01 08:41:20', 'CGATE 253', '1.00', ''),
(364, 5, 1, '2014-02-01 08:42:31', 'CGATE 253', '1.00', ''),
(365, 5, 1, '2014-02-01 08:45:38', 'CGATE 253', '1.00', ''),
(366, 5, 1, '2014-02-01 08:49:52', 'CGATE 253', '1.00', ''),
(367, 5, 1, '2014-02-01 08:54:43', 'CGATE 253', '-1.00', 'tours'),
(368, 9, 1, '2014-02-03 01:59:13', 'CGATE 254', '-1.00', 'tours'),
(369, 9, 1, '2014-02-03 03:04:04', 'CGATE 255', '-1.00', 'tours'),
(370, 7, 1, '2014-02-03 03:16:22', 'CGATE 256', '-1.00', 'tours'),
(371, 15, 1, '2014-02-03 03:18:04', 'CGATE 257', '-1.00', 'tours'),
(372, 15, 1, '2014-02-03 03:21:02', 'CGATE 257', '1.00', ''),
(373, 15, 1, '2014-02-03 03:21:02', 'CGATE 257', '-1.00', 'tours'),
(374, 15, 1, '2014-02-03 03:24:03', 'CGATE 257', '1.00', ''),
(375, 15, 1, '2014-02-03 03:24:03', 'CGATE 257', '-1.00', 'tours'),
(376, 15, 1, '2014-02-03 03:26:25', 'CGATE 257', '1.00', ''),
(377, 15, 1, '2014-02-03 03:26:26', 'CGATE 257', '-1.00', 'tours'),
(378, 15, 1, '2014-02-03 03:27:53', 'CGATE 257', '1.00', ''),
(379, 15, 1, '2014-02-03 03:27:53', 'CGATE 257', '-1.00', 'tours'),
(380, 15, 1, '2014-02-03 03:30:42', 'CGATE 257', '1.00', ''),
(381, 15, 1, '2014-02-03 03:30:43', 'CGATE 257', '-1.00', 'tours'),
(382, 15, 1, '2014-02-03 03:34:09', 'CGATE 257', '1.00', ''),
(383, 15, 1, '2014-02-03 03:34:10', 'CGATE 257', '-1.00', 'tours'),
(384, 15, 1, '2014-02-03 03:35:44', 'CGATE 257', '1.00', ''),
(385, 15, 1, '2014-02-03 03:35:44', 'CGATE 257', '-1.00', 'tours'),
(386, 15, 1, '2014-02-03 03:37:33', 'CGATE 257', '1.00', ''),
(387, 15, 1, '2014-02-03 03:37:34', 'CGATE 257', '-1.00', 'tours'),
(388, 15, 1, '2014-02-03 03:46:00', 'CGATE 257', '1.00', ''),
(389, 15, 1, '2014-02-03 03:46:00', 'CGATE 257', '-1.00', 'tours'),
(390, 15, 1, '2014-02-03 03:48:00', 'CGATE 257', '1.00', ''),
(391, 15, 1, '2014-02-03 03:48:00', 'CGATE 257', '-1.00', 'tours'),
(392, 15, 1, '2014-02-03 03:51:56', 'CGATE 257', '1.00', ''),
(393, 15, 1, '2014-02-03 03:51:56', 'CGATE 257', '-1.00', 'tours'),
(394, 15, 1, '2014-02-03 03:54:26', 'CGATE 257', '1.00', ''),
(395, 15, 1, '2014-02-03 03:54:27', 'CGATE 257', '-1.00', 'tours'),
(396, 15, 1, '2014-02-03 04:33:08', 'CGATE 257', '1.00', ''),
(397, 15, 1, '2014-02-03 04:33:08', 'CGATE 257', '-1.00', 'tours'),
(398, 15, 1, '2014-02-03 04:44:28', 'CGATE 257', '1.00', ''),
(399, 15, 1, '2014-02-03 04:44:28', 'CGATE 257', '-1.00', 'tours'),
(400, 15, 1, '2014-02-03 04:53:39', 'CGATE 257', '1.00', ''),
(401, 15, 1, '2014-02-03 04:53:40', 'CGATE 257', '-1.00', 'tours'),
(402, 15, 1, '2014-02-03 06:55:47', 'CGATE 257', '1.00', ''),
(403, 15, 1, '2014-02-03 06:55:48', 'CGATE 257', '-1.00', 'tours'),
(404, 15, 1, '2014-02-03 06:58:01', 'CGATE 257', '1.00', ''),
(405, 15, 1, '2014-02-03 06:58:02', 'CGATE 257', '-1.00', 'tours'),
(406, 35, 1, '2014-02-04 03:14:56', 'CGATE 258', '-1.00', ''),
(407, 40, 1, '2014-02-04 06:39:22', 'CGATE 259', '-1.00', ''),
(448, 38, 1, '2014-02-05 03:57:39', 'CGATE 301', '-1.00', 'tickets'),
(454, 39, 1, '2014-02-05 06:50:18', 'CGATE 311', '-1.00', 'tickets'),
(456, 43, 1, '2014-02-05 07:06:06', 'CGATE 313', '-1.00', 'tickets'),
(457, 14, 1, '2014-02-05 07:07:38', 'CGATE 314', '-1.00', 'tours'),
(458, 14, 1, '2014-02-05 07:48:52', 'CGATE 315', '-1.00', 'tours'),
(459, 15, 1, '2014-02-05 07:55:03', 'CGATE 316', '-1.00', 'tours'),
(460, 15, 1, '2014-02-05 07:58:03', 'CGATE 316', '1.00', ''),
(461, 15, 1, '2014-02-05 07:58:04', 'CGATE 316', '-1.00', 'tours'),
(462, 5, 1, '2014-02-05 18:15:12', 'CGATE 253', '1.00', ''),
(463, 5, 1, '2014-02-05 18:15:12', 'CGATE 253', '-1.00', 'tours'),
(464, 43, 1, '2014-02-05 18:23:05', 'CGATE 313', '1.00', ''),
(465, 43, 1, '2014-02-05 18:23:06', 'CGATE 313', '-1.00', 'tickets'),
(466, 35, 1, '2014-02-06 01:10:13', 'CGATE 258', '1.00', ''),
(467, 35, 1, '2014-02-06 01:10:13', 'CGATE 258', '-1.00', 'tickets'),
(468, 42, 1, '2014-02-06 01:16:46', 'CGATE 317', '-1.00', 'tickets'),
(469, 42, 1, '2014-02-06 01:30:44', 'CGATE 317', '1.00', ''),
(470, 42, 1, '2014-02-06 01:30:45', 'CGATE 317', '-1.00', 'tickets'),
(471, 42, 1, '2014-02-06 01:32:32', 'CGATE 317', '1.00', ''),
(472, 42, 1, '2014-02-06 01:32:33', 'CGATE 317', '-1.00', 'tickets'),
(473, 42, 1, '2014-02-06 01:57:19', 'CGATE 317', '1.00', ''),
(474, 42, 1, '2014-02-06 01:57:20', 'CGATE 317', '-1.00', 'tickets'),
(475, 41, 1, '2014-02-06 01:57:20', 'CGATE 317', '-1.00', 'tickets'),
(476, 42, 1, '2014-02-06 02:25:11', 'CGATE 317', '1.00', ''),
(477, 41, 1, '2014-02-06 02:25:11', 'CGATE 317', '1.00', ''),
(478, 42, 1, '2014-02-06 02:25:12', 'CGATE 317', '-1.00', 'tickets'),
(479, 41, 1, '2014-02-06 02:25:12', 'CGATE 317', '-1.00', 'tickets'),
(480, 42, 1, '2014-02-06 02:52:33', 'CGATE 317', '1.00', ''),
(481, 41, 1, '2014-02-06 02:52:33', 'CGATE 317', '1.00', ''),
(482, 42, 1, '2014-02-06 02:53:27', 'CGATE 317', '1.00', ''),
(483, 41, 1, '2014-02-06 02:53:27', 'CGATE 317', '1.00', ''),
(484, 42, 1, '2014-02-06 02:56:05', 'CGATE 317', '1.00', ''),
(485, 41, 1, '2014-02-06 02:56:05', 'CGATE 317', '1.00', ''),
(486, 42, 1, '2014-02-06 02:56:24', 'CGATE 317', '1.00', ''),
(487, 41, 1, '2014-02-06 02:56:24', 'CGATE 317', '1.00', ''),
(488, 42, 1, '2014-02-06 03:00:53', 'CGATE 317', '1.00', ''),
(489, 41, 1, '2014-02-06 03:00:54', 'CGATE 317', '1.00', ''),
(490, 42, 1, '2014-02-06 03:05:10', 'CGATE 317', '1.00', ''),
(491, 41, 1, '2014-02-06 03:05:10', 'CGATE 317', '1.00', ''),
(492, 42, 1, '2014-02-06 03:05:15', 'CGATE 317', '1.00', ''),
(493, 41, 1, '2014-02-06 03:05:16', 'CGATE 317', '1.00', ''),
(494, 42, 1, '2014-02-06 03:05:48', 'CGATE 317', '1.00', ''),
(495, 41, 1, '2014-02-06 03:05:48', 'CGATE 317', '1.00', ''),
(496, 42, 1, '2014-02-06 03:20:11', 'CGATE 317', '-1.00', 'tickets'),
(497, 41, 1, '2014-02-06 03:20:11', 'CGATE 317', '-1.00', 'tickets'),
(498, 1, 1, '2014-02-06 03:25:34', 'CGATE 317', '-1.00', 'tickets'),
(499, 1, 1, '2014-02-06 03:28:36', 'CGATE 317', '1.00', ''),
(500, 1, 1, '2014-02-06 03:28:37', 'CGATE 317', '-1.00', 'tickets'),
(501, 1, 1, '2014-02-06 03:29:53', 'CGATE 317', '1.00', ''),
(502, 1, 1, '2014-02-06 03:31:55', 'CGATE 317', '1.00', ''),
(503, 1, 1, '2014-02-06 03:33:54', 'CGATE 317', '-1.00', 'tickets'),
(504, 21, 1, '2014-02-06 03:48:04', 'CGATE 317', '-1.00', 'tickets'),
(505, 21, 1, '2014-02-06 03:49:06', 'CGATE 317', '1.00', ''),
(506, 21, 1, '2014-02-06 03:49:07', 'CGATE 317', '-1.00', 'tickets'),
(507, 37, 1, '2014-02-06 03:58:36', 'CGATE 317', '-1.00', 'tickets'),
(508, 37, 1, '2014-02-06 04:00:12', 'CGATE 317', '1.00', ''),
(509, 37, 1, '2014-02-06 04:00:13', 'CGATE 317', '-1.00', 'tickets'),
(510, 44, 1, '2014-02-06 04:02:39', 'CGATE 317', '-1.00', 'tickets'),
(511, 44, 1, '2014-02-06 04:03:52', 'CGATE 317', '1.00', ''),
(512, 44, 1, '2014-02-06 04:03:53', 'CGATE 317', '-1.00', 'tickets'),
(513, 17, 1, '2014-02-06 04:07:20', 'CGATE 317', '-1.00', 'tickets'),
(514, 17, 1, '2014-02-06 04:07:49', 'CGATE 317', '1.00', ''),
(515, 17, 1, '2014-02-06 04:07:49', 'CGATE 317', '-1.00', 'tickets'),
(516, 37, 1, '2014-02-06 04:19:41', 'CGATE 317', '-1.00', 'tickets'),
(517, 37, 1, '2014-02-06 04:39:42', 'CGATE 317', '1.00', ''),
(518, 39, 1, '2014-02-06 04:39:42', 'CGATE 317', '1.00', ''),
(519, 37, 1, '2014-02-06 04:39:43', 'CGATE 317', '-1.00', 'tickets'),
(520, 39, 1, '2014-02-06 04:39:43', 'CGATE 317', '-1.00', 'tickets'),
(521, 37, 1, '2014-02-06 04:42:08', 'CGATE 317', '1.00', ''),
(522, 39, 1, '2014-02-06 04:42:09', 'CGATE 317', '1.00', ''),
(523, 37, 1, '2014-02-06 04:42:10', 'CGATE 317', '-1.00', 'tickets'),
(524, 39, 1, '2014-02-06 04:42:10', 'CGATE 317', '-1.00', 'tickets'),
(525, 37, 1, '2014-02-06 04:43:02', 'CGATE 317', '1.00', ''),
(526, 39, 1, '2014-02-06 04:43:02', 'CGATE 317', '1.00', ''),
(527, 37, 1, '2014-02-06 04:43:03', 'CGATE 317', '-1.00', 'tickets'),
(528, 39, 1, '2014-02-06 04:43:03', 'CGATE 317', '-1.00', 'tickets'),
(529, 37, 1, '2014-02-06 04:48:29', 'CGATE 317', '1.00', ''),
(530, 39, 1, '2014-02-06 04:48:29', 'CGATE 317', '1.00', ''),
(531, 39, 1, '2014-02-06 04:48:31', 'CGATE 317', '-1.00', 'tickets'),
(532, 39, 1, '2014-02-06 04:50:55', 'CGATE 317', '1.00', ''),
(533, 39, 1, '2014-02-06 04:50:56', 'CGATE 317', '-1.00', 'tickets'),
(534, 37, 1, '2014-02-06 04:50:56', 'CGATE 317', '-1.00', 'tickets'),
(535, 39, 1, '2014-02-06 07:59:48', 'CGATE 317', '1.00', ''),
(536, 37, 1, '2014-02-06 07:59:48', 'CGATE 317', '1.00', ''),
(537, 39, 1, '2014-02-06 07:59:50', 'CGATE 317', '-1.00', 'tickets'),
(538, 37, 1, '2014-02-06 07:59:50', 'CGATE 317', '-1.00', 'tickets'),
(539, 40, 1, '2014-02-06 08:10:48', 'CGATE 318', '-1.00', 'tickets'),
(540, 35, 1, '2014-02-06 08:10:48', 'CGATE 318', '-1.00', 'tickets'),
(541, 40, 1, '2014-02-06 08:14:30', 'CGATE 318', '1.00', ''),
(542, 35, 1, '2014-02-06 08:14:31', 'CGATE 318', '1.00', ''),
(543, 40, 1, '2014-02-06 08:14:31', 'CGATE 318', '-1.00', 'tickets'),
(544, 35, 1, '2014-02-06 08:14:32', 'CGATE 318', '-1.00', 'tickets'),
(545, 35, 1, '2014-02-07 03:29:16', 'CGATE 258', '1.00', ''),
(546, 35, 1, '2014-02-07 03:29:18', 'CGATE 258', '-1.00', 'tickets'),
(547, 35, 1, '2014-02-07 03:36:55', 'CGATE 258', '1.00', ''),
(548, 35, 1, '2014-02-07 03:36:56', 'CGATE 258', '-1.00', 'tickets'),
(549, 38, 1, '2014-02-07 04:17:44', 'CGATE 319', '-1.00', 'tickets'),
(550, 44, 1, '2014-02-07 04:17:44', 'CGATE 319', '-1.00', 'tickets'),
(551, 38, 1, '2014-02-07 04:19:30', 'CGATE 319', '1.00', ''),
(552, 44, 1, '2014-02-07 04:19:30', 'CGATE 319', '1.00', ''),
(553, 38, 1, '2014-02-07 04:19:31', 'CGATE 319', '-1.00', 'tickets'),
(554, 44, 1, '2014-02-07 04:19:31', 'CGATE 319', '-1.00', 'tickets'),
(555, 2, 1, '2014-02-07 16:40:50', 'CGATE 320', '-1.00', 'tickets'),
(556, 18, 1, '2014-02-07 16:44:45', 'CGATE 320', '-1.00', 'tickets'),
(557, 19, 1, '2014-02-07 17:25:01', 'CGATE 320', '-1.00', 'tickets'),
(558, 43, 1, '2014-02-07 17:29:35', 'CGATE 321', '-1.00', 'tickets'),
(559, 34, 1, '2014-02-07 17:35:04', 'CGATE 321', '-1.00', 'tickets'),
(560, 36, 1, '2014-02-07 17:44:08', 'CGATE 321', '-1.00', 'tickets'),
(569, 42, 1, '2014-02-07 17:58:58', 'CGATE 330', '-1.00', 'tickets'),
(570, 42, 1, '2014-02-07 17:59:51', 'CGATE 330', '1.00', ''),
(571, 42, 1, '2014-02-07 17:59:52', 'CGATE 330', '-1.00', 'tickets'),
(572, 41, 1, '2014-02-07 17:59:52', 'CGATE 330', '-1.00', 'tickets'),
(573, 9, 1, '2014-02-07 18:04:07', 'CGATE 331', '-1.00', 'tours'),
(574, 9, 1, '2014-02-07 18:20:51', 'CGATE 331', '1.00', ''),
(575, 9, 1, '2014-02-07 18:20:52', 'CGATE 331', '-1.00', 'tours'),
(579, 3, 1, '2014-02-08 13:43:04', 'CGATE 338', '-1.00', 'massages'),
(580, 3, 1, '2014-02-08 17:00:19', 'CGATE 339', '-1.00', 'massages'),
(581, 39, 1, '2014-02-09 16:58:52', 'CGATE 340', '-1.00', 'tickets'),
(582, 42, 1, '2014-02-09 16:58:52', 'CGATE 340', '-1.00', 'tickets'),
(583, -1, 1, '2014-02-10 03:01:00', 'Manual Edit of Quantity', '0.00', 'tickets'),
(584, 51, 1, '2014-02-10 03:38:07', 'CGATE 341', '-1.00', 'tickets'),
(585, 51, 1, '2014-02-10 04:11:10', 'CGATE 341', '1.00', 'tickets'),
(586, 51, 1, '2014-02-10 04:11:10', 'CGATE 341', '-1.00', 'tickets'),
(587, 16, 1, '2014-02-11 02:10:10', 'Manual Edit of Quantity', '0.00', 'tours'),
(588, 51, 1, '2014-02-11 03:25:09', 'Manual Edit of Quantity', '-1.00', 'tickets'),
(589, 51, 1, '2014-02-11 03:25:31', 'Manual Edit of Quantity', '-1.00', 'tickets'),
(590, 51, 1, '2014-02-11 03:26:06', 'Manual Edit of Quantity', '-1.00', 'tickets'),
(591, 51, 1, '2014-02-11 03:27:38', 'Manual Edit of Quantity', '-1.00', 'tickets'),
(592, 51, 1, '2014-02-11 04:26:18', 'Manual Edit of Quantity', '-1.00', 'tickets'),
(593, 51, 1, '2014-02-11 04:26:44', 'Manual Edit of Quantity', '-1.00', 'tickets'),
(594, 51, 1, '2014-02-11 04:27:17', 'Manual Edit of Quantity', '-1.00', 'tickets'),
(595, 51, 1, '2014-02-11 04:27:20', 'Manual Edit of Quantity', '-1.00', 'tickets'),
(596, 51, 1, '2014-02-11 04:31:26', 'Manual Edit of Quantity', '-1.00', 'tickets'),
(597, 51, 1, '2014-02-11 06:46:43', 'Manual Edit of Quantity', '-1.00', 'tickets'),
(598, 51, 1, '2014-02-11 06:46:43', 'Manual Edit of Quantity', '-1.00', 'tickets'),
(599, 51, 1, '2014-02-11 06:47:09', 'Manual Edit of Quantity', '-1.00', 'tickets'),
(600, 51, 1, '2014-02-11 07:16:24', 'Manual Edit of Quantity', '-1.00', 'tickets'),
(601, 51, 1, '2014-02-11 07:24:20', 'Manual Edit of Quantity', '-1.00', 'tickets'),
(602, 51, 1, '2014-02-11 07:26:33', 'Manual Edit of Quantity', '-1.00', 'tickets'),
(603, 51, 1, '2014-02-11 07:30:47', 'Manual Edit of Quantity', '-1.00', 'tickets'),
(604, 51, 1, '2014-02-11 07:31:37', 'Manual Edit of Quantity', '-1.00', 'tickets'),
(605, 51, 1, '2014-02-11 07:33:17', 'Manual Edit of Quantity', '-1.00', 'tickets'),
(606, 48, 1, '2014-02-11 08:30:43', 'Manual Edit of Quantity', '-1.00', 'tickets'),
(607, 51, 1, '2014-02-11 08:31:14', 'Manual Edit of Quantity', '-1.00', 'tickets'),
(608, 51, 1, '2014-02-12 01:16:14', 'Manual Edit of Quantity', '-1.00', 'tickets'),
(609, 51, 1, '2014-02-12 01:16:55', 'Manual Edit of Quantity', '-1.00', 'tickets'),
(610, 51, 1, '2014-02-12 01:19:01', 'Manual Edit of Quantity', '-1.00', 'tickets'),
(611, 51, 1, '2014-02-12 01:23:55', 'Manual Edit of Quantity', '-1.00', 'tickets'),
(612, 51, 1, '2014-02-12 01:24:21', 'Manual Edit of Quantity', '-1.00', 'tickets'),
(613, 51, 1, '2014-02-12 01:25:51', 'Manual Edit of Quantity', '-1.00', 'tickets'),
(614, 51, 1, '2014-02-12 02:25:24', 'Manual Edit of Quantity', '-1.00', 'tickets'),
(615, 51, 1, '2014-02-12 02:40:00', 'CGATE 342', '-1.00', 'tickets'),
(616, 47, 1, '2014-02-12 02:40:00', 'CGATE 342', '-1.00', 'tickets'),
(617, 51, 1, '2014-02-12 02:42:05', 'CGATE 342', '1.00', 'tickets'),
(618, 47, 1, '2014-02-12 02:42:05', 'CGATE 342', '1.00', 'tickets'),
(619, 51, 1, '2014-02-12 02:42:06', 'CGATE 342', '-1.00', 'tickets'),
(620, 47, 1, '2014-02-12 02:42:06', 'CGATE 342', '-1.00', 'tickets'),
(621, 9, 1, '2014-02-12 03:12:45', 'CGATE 343', '-1.00', 'tours'),
(622, 8, 1, '2014-02-12 03:12:45', 'CGATE 343', '-1.00', 'tours'),
(623, 51, 1, '2014-02-12 03:38:19', 'Manual Edit of Quantity', '-1.00', 'tickets'),
(624, 51, 1, '2014-02-12 03:40:00', 'Manual Edit of Quantity', '-1.00', 'tickets'),
(625, 51, 1, '2014-02-12 03:42:54', 'Manual Edit of Quantity', '-1.00', 'tickets'),
(626, 51, 1, '2014-02-12 03:43:13', 'Manual Edit of Quantity', '-1.00', 'tickets'),
(627, 51, 1, '2014-02-12 03:47:04', 'Manual Edit of Quantity', '-1.00', 'tickets'),
(628, 51, 1, '2014-02-12 03:49:44', 'Manual Edit of Quantity', '-1.00', 'tickets'),
(629, 51, 1, '2014-02-12 03:51:47', 'Manual Edit of Quantity', '-1.00', 'tickets'),
(630, 51, 1, '2014-02-12 03:52:06', 'Manual Edit of Quantity', '-1.00', 'tickets'),
(631, 51, 1, '2014-02-12 03:52:56', 'Manual Edit of Quantity', '-1.00', 'tickets'),
(632, 51, 1, '2014-02-12 03:54:37', 'Manual Edit of Quantity', '-1.00', 'tickets'),
(633, 51, 1, '2014-02-12 04:05:56', 'Manual Edit of Quantity', '-1.00', 'tickets'),
(634, 51, 1, '2014-02-12 04:46:51', 'CGATE 344', '-1.00', 'tickets'),
(635, 47, 1, '2014-02-12 04:46:51', 'CGATE 344', '-1.00', 'tickets'),
(636, 51, 1, '2014-02-12 07:57:40', 'CGATE 344', '1.00', 'tickets'),
(637, 47, 1, '2014-02-12 07:57:40', 'CGATE 344', '1.00', 'tickets'),
(638, 51, 1, '2014-02-12 07:57:41', 'CGATE 344', '-1.00', 'tickets'),
(639, 47, 1, '2014-02-12 07:57:41', 'CGATE 344', '-1.00', 'tickets'),
(640, 51, 1, '2014-02-12 17:06:44', 'CGATE 344', '1.00', 'tickets'),
(641, 47, 1, '2014-02-12 17:06:45', 'CGATE 344', '1.00', 'tickets'),
(642, 51, 1, '2014-02-12 17:06:45', 'CGATE 344', '-1.00', 'tickets'),
(643, 47, 1, '2014-02-12 17:06:45', 'CGATE 344', '-1.00', 'tickets'),
(644, 51, 1, '2014-02-12 17:11:59', 'CGATE 344', '1.00', 'tickets'),
(645, 47, 1, '2014-02-12 17:11:59', 'CGATE 344', '1.00', 'tickets'),
(646, 51, 1, '2014-02-12 17:11:59', 'CGATE 344', '-1.00', 'tickets'),
(647, 47, 1, '2014-02-12 17:12:00', 'CGATE 344', '-1.00', 'tickets'),
(648, 51, 1, '2014-02-12 17:41:45', 'CGATE 344', '1.00', 'tickets'),
(649, 47, 1, '2014-02-12 17:41:45', 'CGATE 344', '1.00', 'tickets'),
(650, 51, 1, '2014-02-12 17:41:46', 'CGATE 344', '-1.00', 'tickets'),
(651, 47, 1, '2014-02-12 17:41:46', 'CGATE 344', '-1.00', 'tickets'),
(652, 51, 1, '2014-02-12 17:52:42', 'CGATE 344', '1.00', 'tickets'),
(653, 47, 1, '2014-02-12 17:52:42', 'CGATE 344', '1.00', 'tickets'),
(654, 51, 1, '2014-02-12 17:52:43', 'CGATE 344', '-1.00', 'tickets'),
(655, 47, 1, '2014-02-12 17:52:43', 'CGATE 344', '-1.00', 'tickets'),
(656, 51, 1, '2014-02-13 02:33:45', 'CGATE 344', '1.00', 'tickets'),
(657, 47, 1, '2014-02-13 02:33:45', 'CGATE 344', '1.00', 'tickets'),
(658, 51, 1, '2014-02-13 02:33:46', 'CGATE 344', '-1.00', 'tickets'),
(659, 47, 1, '2014-02-13 02:33:46', 'CGATE 344', '-1.00', 'tickets'),
(660, 51, 1, '2014-02-13 02:34:51', 'CGATE 344', '1.00', 'tickets'),
(661, 47, 1, '2014-02-13 02:34:51', 'CGATE 344', '1.00', 'tickets'),
(662, 51, 1, '2014-02-13 02:34:52', 'CGATE 344', '-1.00', 'tickets'),
(663, 47, 1, '2014-02-13 02:34:52', 'CGATE 344', '-1.00', 'tickets'),
(664, 17, 1, '2014-02-13 04:05:31', 'Manual Edit of Quantity', '0.00', 'tours'),
(665, 51, 1, '2014-02-13 06:19:01', 'CGATE 344', '1.00', 'tickets'),
(666, 47, 1, '2014-02-13 06:19:01', 'CGATE 344', '1.00', 'tickets'),
(667, 51, 1, '2014-02-13 06:19:02', 'CGATE 344', '-1.00', 'tickets'),
(668, 47, 1, '2014-02-13 06:19:03', 'CGATE 344', '-1.00', 'tickets'),
(669, 51, 1, '2014-02-13 06:27:51', 'CGATE 344', '1.00', 'tickets'),
(670, 47, 1, '2014-02-13 06:27:51', 'CGATE 344', '1.00', 'tickets'),
(671, 51, 1, '2014-02-13 06:27:51', 'CGATE 344', '-1.00', 'tickets'),
(672, 47, 1, '2014-02-13 06:27:52', 'CGATE 344', '-1.00', 'tickets'),
(673, 51, 1, '2014-02-13 06:35:11', 'CGATE 344', '1.00', 'tickets'),
(674, 47, 1, '2014-02-13 06:35:11', 'CGATE 344', '1.00', 'tickets'),
(675, 51, 1, '2014-02-13 06:35:12', 'CGATE 344', '-1.00', 'tickets'),
(676, 47, 1, '2014-02-13 06:35:12', 'CGATE 344', '-1.00', 'tickets'),
(677, 51, 1, '2014-02-13 06:56:16', 'CGATE 344', '1.00', 'tickets'),
(678, 47, 1, '2014-02-13 06:56:16', 'CGATE 344', '1.00', 'tickets'),
(679, 51, 1, '2014-02-13 06:56:17', 'CGATE 344', '-1.00', 'tickets'),
(680, 47, 1, '2014-02-13 06:56:17', 'CGATE 344', '-1.00', 'tickets'),
(681, 51, 1, '2014-02-13 07:00:42', 'CGATE 344', '1.00', 'tickets'),
(682, 47, 1, '2014-02-13 07:00:42', 'CGATE 344', '1.00', 'tickets'),
(683, 51, 1, '2014-02-13 07:00:42', 'CGATE 344', '-1.00', 'tickets'),
(684, 47, 1, '2014-02-13 07:00:43', 'CGATE 344', '-1.00', 'tickets'),
(685, 51, 1, '2014-02-14 03:14:19', 'CGATE 344', '1.00', 'tickets'),
(686, 47, 1, '2014-02-14 03:14:19', 'CGATE 344', '1.00', 'tickets'),
(687, 51, 1, '2014-02-14 03:14:21', 'CGATE 344', '-1.00', 'tickets'),
(688, 47, 1, '2014-02-14 03:14:21', 'CGATE 344', '-1.00', 'tickets'),
(689, 52, 1, '2014-02-14 03:21:28', 'Manual Edit of Quantity', '0.00', 'tickets'),
(690, 52, 1, '2014-02-14 03:25:57', 'Manual Edit of Quantity', '-1.00', 'tickets'),
(691, 52, 1, '2014-02-14 03:26:40', 'Manual Edit of Quantity', '-1.00', 'tickets'),
(692, 52, 1, '2014-02-14 03:27:03', 'Manual Edit of Quantity', '-1.00', 'tickets'),
(693, 47, 1, '2014-02-14 04:07:45', 'CGATE 345', '-1.00', 'tickets'),
(694, 51, 1, '2014-02-14 04:07:45', 'CGATE 345', '-1.00', 'tickets'),
(695, 51, 1, '2014-02-15 03:52:09', 'Manual Edit of Quantity', '-1.00', 'tickets'),
(696, 51, 1, '2014-02-17 02:30:02', 'CGATE 344', '1.00', 'tickets'),
(697, 47, 1, '2014-02-17 02:30:02', 'CGATE 344', '1.00', 'tickets'),
(698, 51, 1, '2014-02-17 02:30:03', 'CGATE 344', '-1.00', 'tickets'),
(699, 47, 1, '2014-02-17 02:30:03', 'CGATE 344', '-1.00', 'tickets'),
(700, 51, 1, '2014-02-18 03:05:41', 'CGATE 344', '1.00', 'tickets'),
(701, 47, 1, '2014-02-18 03:05:41', 'CGATE 344', '1.00', 'tickets'),
(702, 51, 1, '2014-02-18 03:05:42', 'CGATE 344', '-1.00', 'tickets'),
(703, 47, 1, '2014-02-18 03:05:42', 'CGATE 344', '-1.00', 'tickets'),
(704, 51, 1, '2014-02-24 07:26:50', 'CGATE 344', '1.00', 'tickets'),
(705, 47, 1, '2014-02-24 07:26:51', 'CGATE 344', '1.00', 'tickets'),
(706, 51, 1, '2014-02-24 07:26:51', 'CGATE 344', '-1.00', 'tickets'),
(707, 47, 1, '2014-02-24 07:26:52', 'CGATE 344', '-1.00', 'tickets'),
(708, 51, 1, '2014-02-24 07:27:03', 'CGATE 344', '1.00', 'tickets'),
(709, 47, 1, '2014-02-24 07:27:04', 'CGATE 344', '1.00', 'tickets'),
(710, 51, 1, '2014-02-24 07:27:04', 'CGATE 344', '-1.00', 'tickets'),
(711, 47, 1, '2014-02-24 07:27:04', 'CGATE 344', '-1.00', 'tickets'),
(712, 50, 1, '2014-02-24 07:27:48', 'CGATE 346', '-1.00', 'tickets'),
(713, 50, 1, '2014-02-24 07:28:52', 'CGATE 347', '-1.00', 'tickets'),
(714, 51, 1, '2014-02-24 07:28:53', 'CGATE 347', '-1.00', 'tickets'),
(715, 50, 1, '2014-02-24 07:30:19', 'CGATE 348', '-1.00', 'tickets'),
(716, 51, 1, '2014-02-24 07:30:19', 'CGATE 348', '-1.00', 'tickets'),
(717, 48, 1, '2014-02-25 01:09:22', 'CGATE 349', '-1.00', 'tickets'),
(718, 48, 1, '2014-02-25 01:17:36', 'CGATE 350', '-1.00', 'tickets'),
(719, 49, 1, '2014-02-25 01:31:59', 'CGATE 351', '-1.00', 'tickets'),
(720, 48, 1, '2014-02-25 02:23:27', 'CGATE 352', '-1.00', 'tickets'),
(721, 48, 1, '2014-02-26 01:20:10', 'CGATE 352', '1.00', 'tickets'),
(722, 48, 1, '2014-02-26 01:20:11', 'CGATE 352', '-1.00', 'tickets'),
(723, 46, 1, '2014-02-26 01:26:50', 'CGATE 352', '-1.00', 'tickets'),
(724, 46, 1, '2014-02-26 02:18:12', 'CGATE 352', '1.00', 'tickets'),
(725, 46, 1, '2014-02-26 02:18:13', 'CGATE 352', '-1.00', 'tickets'),
(726, 46, 1, '2014-02-26 02:20:51', 'CGATE 352', '1.00', 'tickets'),
(727, 46, 1, '2014-02-26 02:20:52', 'CGATE 352', '-1.00', 'tickets'),
(728, 46, 1, '2014-02-26 02:25:05', 'CGATE 352', '1.00', 'tickets'),
(729, 46, 1, '2014-02-26 02:25:06', 'CGATE 352', '-1.00', 'tickets'),
(730, 51, 1, '2014-02-26 02:27:27', 'CGATE 353', '-1.00', 'tickets'),
(731, 46, 1, '2014-02-26 07:48:27', 'CGATE 352', '1.00', 'tickets'),
(732, 46, 1, '2014-02-26 07:48:28', 'CGATE 352', '-1.00', 'tickets'),
(733, 48, 1, '2014-02-26 07:48:28', 'CGATE 352', '-1.00', 'tickets'),
(734, 7, 1, '2014-03-06 19:19:37', 'CGATE 354', '-1.00', 'massages'),
(735, 48, 1, '2014-03-07 07:26:57', 'CGATE 355', '-1.00', 'tickets'),
(736, 51, 1, '2014-03-07 07:52:09', 'CGATE 356', '-1.00', 'tickets'),
(737, 51, 1, '2014-03-07 10:04:47', 'CGATE 356', '1.00', 'tickets'),
(738, 51, 1, '2014-03-07 10:04:48', 'CGATE 356', '-1.00', 'tickets'),
(739, 51, 1, '2014-03-07 10:14:44', 'CGATE 356', '1.00', 'tickets'),
(740, 51, 1, '2014-03-07 10:14:45', 'CGATE 356', '-1.00', 'tickets'),
(741, 47, 1, '2014-03-07 10:14:45', 'CGATE 356', '-1.00', 'tickets'),
(742, 51, 1, '2014-03-07 10:19:23', 'CGATE 357', '-1.00', 'tickets'),
(743, 47, 1, '2014-03-07 10:19:23', 'CGATE 357', '-1.00', 'tickets'),
(744, 51, 1, '2014-03-07 10:20:57', 'CGATE 357', '1.00', 'tickets'),
(745, 47, 1, '2014-03-07 10:20:57', 'CGATE 357', '1.00', 'tickets'),
(746, 51, 1, '2014-03-07 10:20:58', 'CGATE 357', '-1.00', 'tickets'),
(747, 47, 1, '2014-03-07 10:20:58', 'CGATE 357', '-1.00', 'tickets'),
(748, 51, 1, '2014-03-07 13:12:15', 'CGATE 358', '-1.00', 'tickets'),
(749, 47, 1, '2014-03-07 13:12:15', 'CGATE 358', '-1.00', 'tickets'),
(750, 51, 1, '2014-03-07 13:15:20', 'CGATE 358', '1.00', 'tickets'),
(751, 47, 1, '2014-03-07 13:15:20', 'CGATE 358', '1.00', 'tickets'),
(752, 51, 1, '2014-03-07 13:15:22', 'CGATE 358', '-1.00', 'tickets'),
(753, 47, 1, '2014-03-07 13:15:22', 'CGATE 358', '-1.00', 'tickets'),
(754, 48, 1, '2014-03-07 14:07:55', 'CGATE 355', '1.00', 'tickets'),
(755, 48, 1, '2014-03-07 14:07:55', 'CGATE 355', '-1.00', 'tickets'),
(756, 51, 1, '2014-03-07 14:08:54', 'CGATE 358', '1.00', 'tickets'),
(757, 47, 1, '2014-03-07 14:08:54', 'CGATE 358', '1.00', 'tickets'),
(758, 51, 1, '2014-03-07 14:08:55', 'CGATE 358', '-1.00', 'tickets'),
(759, 47, 1, '2014-03-07 14:08:56', 'CGATE 358', '-1.00', 'tickets'),
(760, 51, 1, '2014-03-08 10:48:20', 'CGATE 359', '-1.00', 'tickets'),
(761, 51, 1, '2014-03-11 08:41:06', 'Manual Edit of Quantity', '-1.00', 'tickets'),
(762, 47, 1, '2014-03-11 08:47:27', 'CGATE 360', '-1.00', 'tickets'),
(763, 48, 1, '2014-03-14 00:58:58', 'Manual Edit of Quantity', '-1.00', 'tickets'),
(764, 18, 1, '2014-03-14 01:02:36', 'Manual Edit of Quantity', '0.00', 'tours'),
(765, 19, 1, '2014-03-14 01:03:49', 'Manual Edit of Quantity', '0.00', 'tours'),
(766, 19, 1, '2014-03-14 01:16:54', 'Manual Edit of Quantity', '0.00', 'tours'),
(767, 19, 1, '2014-03-14 01:20:06', 'Manual Edit of Quantity', '0.00', 'tours'),
(768, 31, 1, '2014-03-13 02:54:56', 'Manual Edit of Quantity', '0.00', 'tickets'),
(769, 31, 1, '2014-03-13 02:55:33', 'Manual Edit of Quantity', '0.00', 'tickets'),
(770, 31, 1, '2014-03-13 02:58:04', 'Manual Edit of Quantity', '0.00', 'tickets'),
(771, 31, 1, '2014-03-13 02:58:34', 'Manual Edit of Quantity', '0.00', 'tickets'),
(772, 31, 1, '2014-03-13 03:00:44', 'Manual Edit of Quantity', '0.00', 'tickets'),
(773, -1, 1, '2014-03-13 03:30:02', 'Manual Edit of Quantity', '0.00', 'tickets'),
(774, 54, 1, '2014-03-13 03:36:13', 'CGATE 361', '-1.00', 'tickets'),
(775, 50, 1, '2014-03-13 03:36:13', 'CGATE 361', '-1.00', 'tickets'),
(776, 51, 1, '2014-03-13 03:39:41', 'CGATE 359', '1.00', 'tickets'),
(777, 51, 1, '2014-03-13 03:39:41', 'CGATE 359', '-1.00', 'tickets'),
(778, 8, 1, '2014-03-14 18:36:19', 'CGATE 362', '-1.00', 'tours'),
(779, 15, 1, '2014-03-14 20:20:23', 'Manual Edit of Quantity', '0.00', 'tours'),
(780, 15, 1, '2014-03-14 20:42:24', 'Manual Edit of Quantity', '0.00', 'tours'),
(781, 15, 1, '2014-03-14 20:44:26', 'Manual Edit of Quantity', '0.00', 'tours'),
(782, 15, 1, '2014-03-14 20:49:22', 'Manual Edit of Quantity', '0.00', 'tours'),
(783, 9, 1, '2014-03-14 20:52:01', 'Manual Edit of Quantity', '0.00', 'tours'),
(784, 0, 1, '2014-03-14 21:04:56', 'Manual Edit of Quantity', '0.00', 'tours'),
(785, 20, 1, '2014-03-14 22:55:28', 'Manual Edit of Quantity', '0.00', 'tours'),
(786, 20, 1, '2014-03-14 22:58:48', 'Manual Edit of Quantity', '0.00', 'tours'),
(787, 20, 1, '2014-03-14 23:02:05', 'Manual Edit of Quantity', '0.00', 'tours'),
(788, 20, 1, '2014-03-14 23:04:14', 'Manual Edit of Quantity', '0.00', 'tours'),
(789, 20, 1, '2014-03-14 23:05:48', 'Manual Edit of Quantity', '0.00', 'tours'),
(790, 15, 1, '2014-03-14 23:06:41', 'Manual Edit of Quantity', '0.00', 'tours'),
(791, 9, 1, '2014-03-14 23:09:41', 'Manual Edit of Quantity', '0.00', 'tours'),
(792, 8, 1, '2014-03-14 23:26:35', 'Manual Edit of Quantity', '0.00', 'tours'),
(793, 7, 1, '2014-03-14 23:55:29', 'Manual Edit of Quantity', '0.00', 'tours'),
(794, 6, 1, '2014-03-14 23:58:02', 'Manual Edit of Quantity', '0.00', 'tours'),
(795, 18, 1, '2014-03-15 00:37:29', 'Manual Edit of Quantity', '0.00', 'tours'),
(796, 48, 1, '2014-03-15 16:55:15', 'CGATE 364', '-1.00', 'tickets'),
(797, 48, 236, '2014-03-15 16:56:11', 'CGATE 365', '-1.00', 'tickets'),
(798, 49, 236, '2014-03-15 16:59:20', 'CGATE 366', '-1.00', 'tickets'),
(799, 48, 236, '2014-03-15 17:50:10', 'CGATE 367', '-1.00', 'tickets'),
(800, 48, 240, '2014-03-15 18:14:29', 'CGATE 368', '-1.00', 'tickets'),
(801, 51, 1, '2014-03-15 18:30:40', 'CGATE 358', '1.00', 'tickets'),
(802, 47, 1, '2014-03-15 18:30:40', 'CGATE 358', '1.00', 'tickets'),
(803, 51, 1, '2014-03-15 18:30:41', 'CGATE 358', '-1.00', 'tickets'),
(804, 47, 1, '2014-03-15 18:30:42', 'CGATE 358', '-1.00', 'tickets'),
(805, 51, 1, '2014-03-15 18:41:13', 'CGATE 358', '1.00', 'tickets'),
(806, 47, 1, '2014-03-15 18:41:13', 'CGATE 358', '1.00', 'tickets'),
(807, 51, 1, '2014-03-15 18:41:13', 'CGATE 358', '-1.00', 'tickets'),
(808, 47, 1, '2014-03-15 18:41:13', 'CGATE 358', '-1.00', 'tickets'),
(809, 51, 1, '2014-03-15 18:54:38', 'CGATE 358', '1.00', 'tickets'),
(810, 47, 1, '2014-03-15 18:54:38', 'CGATE 358', '1.00', 'tickets'),
(811, 51, 1, '2014-03-15 18:54:39', 'CGATE 358', '-1.00', 'tickets'),
(812, 47, 1, '2014-03-15 18:54:39', 'CGATE 358', '-1.00', 'tickets'),
(813, 51, 1, '2014-03-15 19:00:40', 'CGATE 358', '1.00', 'tickets'),
(814, 47, 1, '2014-03-15 19:00:40', 'CGATE 358', '1.00', 'tickets'),
(815, 51, 1, '2014-03-15 19:00:42', 'CGATE 358', '-1.00', 'tickets'),
(816, 47, 1, '2014-03-15 19:00:42', 'CGATE 358', '-1.00', 'tickets'),
(817, 46, 1, '2014-03-15 19:27:36', 'Manual Edit of Quantity', '-1.00', 'tickets'),
(818, 21, 1, '2014-03-15 19:40:26', 'Manual Edit of Quantity', '0.00', 'tickets'),
(819, 51, 1, '2014-03-15 21:03:43', 'CGATE 369', '-1.00', 'tickets'),
(820, 51, 1, '2014-03-15 21:06:17', 'CGATE 370', '-1.00', 'tickets'),
(821, 46, 1, '2014-03-16 01:32:32', 'Manual Edit of Quantity', '-1.00', 'tickets'),
(822, 46, 1, '2014-03-16 01:32:58', 'Manual Edit of Quantity', '-1.00', 'tickets'),
(823, 46, 1, '2014-03-16 07:04:10', 'CGATE 371', '-2.00', 'tickets'),
(824, 7, 1, '2014-03-17 01:20:07', 'CGATE 372', '-1.00', 'massages'),
(825, 7, 1, '2014-03-17 01:23:19', 'CGATE 373', '-1.00', 'massages');

-- --------------------------------------------------------

--
-- Table structure for table `cgate_items_bikes`
--

CREATE TABLE IF NOT EXISTS `cgate_items_bikes` (
  `item_bike_id` int(10) NOT NULL AUTO_INCREMENT,
  `bike_code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `available` tinyint(1) DEFAULT '1',
  `unit_price` decimal(15,2) NOT NULL,
  `actual_price` decimal(15,2) NOT NULL,
  `bike_types` enum('Khmer Bike','Giant') COLLATE utf8_unicode_ci NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`item_bike_id`),
  KEY `name` (`bike_code`),
  KEY `category` (`available`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;

--
-- Dumping data for table `cgate_items_bikes`
--

INSERT INTO `cgate_items_bikes` (`item_bike_id`, `bike_code`, `available`, `unit_price`, `actual_price`, `bike_types`, `deleted`) VALUES
(1, '00012', 1, '15.00', '10.00', 'Khmer Bike', 0),
(2, '00022', 0, '15.00', '11.00', 'Giant', 1),
(3, '00013a', 1, '12.00', '10.00', 'Giant', 0);

-- --------------------------------------------------------

--
-- Table structure for table `cgate_items_massages`
--

CREATE TABLE IF NOT EXISTS `cgate_items_massages` (
  `item_massage_id` int(10) NOT NULL AUTO_INCREMENT,
  `massage_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `massage_desc` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `supplierID` int(11) DEFAULT NULL,
  `price_one` decimal(15,2) NOT NULL,
  `actual_price` decimal(15,2) NOT NULL,
  `massage_typesID` int(10) NOT NULL,
  `deleted` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`item_massage_id`),
  KEY `phppos_items_ibfk_1` (`supplierID`),
  KEY `name` (`massage_name`),
  KEY `category` (`massage_desc`),
  KEY `deleted` (`deleted`),
  KEY `fk_cgate_items_massages_cgate_massages_types1_idx` (`massage_typesID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=17 ;

--
-- Dumping data for table `cgate_items_massages`
--

INSERT INTO `cgate_items_massages` (`item_massage_id`, `massage_name`, `massage_desc`, `supplierID`, `price_one`, `actual_price`, `massage_typesID`, `deleted`) VALUES
(3, 'massage1', 'massage in the night', 6, '45.00', '0.00', 1, 0),
(6, 'massagetwo', 'sms for showing', 7, '32.00', '0.00', 1, 0),
(7, 'Massage body with oil', 'this is good for ur body,.. make your body smooth and white', 36, '15.00', '0.00', 1, 0),
(8, 'testing', 'hello testing', 7, '15.00', '12.00', 1, 1),
(9, 'Body massage with special oil', 'Hello world jar', 6, '15.00', '12.00', 1, 0),
(16, 'Body massage with special oil', 'Hello world cup', 6, '15.00', '12.00', 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `cgate_items_taxes`
--

CREATE TABLE IF NOT EXISTS `cgate_items_taxes` (
  `item_id` int(10) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `percent` decimal(15,3) NOT NULL,
  `cumulative` int(1) NOT NULL DEFAULT '0',
  `category` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`item_id`,`name`,`percent`,`category`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cgate_item_kits`
--

CREATE TABLE IF NOT EXISTS `cgate_item_kits` (
  `item_kit_id` int(11) NOT NULL AUTO_INCREMENT,
  `item_kit_number` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'For stor record as kit/package',
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `category` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `unit_price` decimal(15,2) DEFAULT NULL,
  `cost_price` decimal(15,2) DEFAULT NULL,
  `deleted` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`item_kit_id`,`category`),
  UNIQUE KEY `item_kit_number` (`item_kit_number`),
  KEY `name` (`name`),
  KEY `deleted` (`deleted`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=5 ;

--
-- Dumping data for table `cgate_item_kits`
--

INSERT INTO `cgate_item_kits` (`item_kit_id`, `item_kit_number`, `name`, `description`, `category`, `unit_price`, `cost_price`, `deleted`) VALUES
(1, '00001', 'Tour to Seim Reap for 10 people', 'We include.....', 'tickets', '10.00', '15.00', 0),
(2, '00002', 'Tour to Kampot for 15 people', 'We add mroe one...', 'tickets', '15.00', '25.00', 0),
(3, '001', 'Ticket tour for 5 people', 'hhhh', 'tickets', '25.00', '30.00', 0),
(4, '00043', 'Tour for tour package', 'This is the testing tour package for tour model', 'tours', '100.00', '90.00', 0);

-- --------------------------------------------------------

--
-- Table structure for table `cgate_item_kits_bikes`
--

CREATE TABLE IF NOT EXISTS `cgate_item_kits_bikes` (
  `item_kit_id` int(10) NOT NULL,
  `item_bike_id` int(10) NOT NULL,
  `quantity` decimal(10,0) NOT NULL,
  PRIMARY KEY (`item_kit_id`,`item_bike_id`,`quantity`),
  KEY `fk_cgate_item_kits_bikes_cgate_item_bikes` (`item_bike_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cgate_item_kits_taxes`
--

CREATE TABLE IF NOT EXISTS `cgate_item_kits_taxes` (
  `item_kit_id` int(10) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `percent` decimal(15,3) NOT NULL,
  `cumulative` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`item_kit_id`,`name`,`percent`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cgate_item_kits_tickets`
--

CREATE TABLE IF NOT EXISTS `cgate_item_kits_tickets` (
  `item_kit_id` int(10) NOT NULL,
  `ticket_id` int(10) NOT NULL,
  `quantity` decimal(10,0) NOT NULL,
  PRIMARY KEY (`ticket_id`,`item_kit_id`,`quantity`),
  KEY `ticket_id` (`ticket_id`),
  KEY `fk_cgate_item_kits_tickets_cgate_item_kits` (`item_kit_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `cgate_item_kits_tickets`
--

INSERT INTO `cgate_item_kits_tickets` (`item_kit_id`, `ticket_id`, `quantity`) VALUES
(2, 1, '1'),
(1, 2, '1'),
(3, 3, '1');

-- --------------------------------------------------------

--
-- Table structure for table `cgate_item_kits_tours`
--

CREATE TABLE IF NOT EXISTS `cgate_item_kits_tours` (
  `item_kit_id` int(11) NOT NULL,
  `tour_id` int(11) NOT NULL,
  `quantity` decimal(10,0) NOT NULL,
  PRIMARY KEY (`tour_id`,`item_kit_id`,`quantity`),
  KEY `fk_item_kit_tour_item_kit` (`item_kit_id`),
  KEY `tour_id` (`tour_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `cgate_item_kit_items`
--

CREATE TABLE IF NOT EXISTS `cgate_item_kit_items` (
  `item_kit_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `quantity` decimal(15,2) NOT NULL,
  PRIMARY KEY (`item_kit_id`,`item_id`,`quantity`),
  KEY `fk_cgate_item_kit_items_cgate_guides1_idx` (`item_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cgate_massages_types`
--

CREATE TABLE IF NOT EXISTS `cgate_massages_types` (
  `massage_type_id` int(10) NOT NULL AUTO_INCREMENT,
  `massage_type_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `deleted` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`massage_type_id`),
  KEY `deleted` (`deleted`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Dumping data for table `cgate_massages_types`
--

INSERT INTO `cgate_massages_types` (`massage_type_id`, `massage_type_name`, `deleted`) VALUES
(1, 'massage one', 0);

-- --------------------------------------------------------

--
-- Table structure for table `cgate_modules`
--

CREATE TABLE IF NOT EXISTS `cgate_modules` (
  `name_lang_key` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `desc_lang_key` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `sort` int(10) NOT NULL,
  `module_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`module_id`),
  UNIQUE KEY `desc_lang_key` (`desc_lang_key`),
  UNIQUE KEY `name_lang_key` (`name_lang_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `cgate_modules`
--

INSERT INTO `cgate_modules` (`name_lang_key`, `desc_lang_key`, `sort`, `module_id`) VALUES
('module_bikes', 'module_bikes_desc', 200, 'bikes'),
('module_commissioners', 'module_commissioners_desc', 110, 'commissioners'),
('module_config', 'module_config_desc', 100, 'config'),
('module_customers', 'module_customers_desc', 10, 'customers'),
('module_employees', 'module_employees_desc', 80, 'employees'),
('module_guides', 'module_guides_desc', 150, 'guides'),
('module_massage', 'module_massage_desc', 120, 'massages'),
('module_reports', 'module_reports_desc', 50, 'reports'),
('module_sales', 'module_sales_desc', 70, 'sales'),
('module_suppliers', 'module_suppliers_desc', 40, 'suppliers'),
('module_tickets', 'module_tickets_desc', 130, 'tickets'),
('module_tours', 'module_tours_desc', 140, 'tours'),
('module_transportation', 'module_transportation_desc', 111, 'transportations');

-- --------------------------------------------------------

--
-- Table structure for table `cgate_modules_actions`
--

CREATE TABLE IF NOT EXISTS `cgate_modules_actions` (
  `action_id` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `module_id` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `action_name_key` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `sort` int(11) NOT NULL,
  PRIMARY KEY (`action_id`,`module_id`),
  KEY `phppos_modules_actions_ibfk_1` (`module_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `cgate_modules_actions`
--

INSERT INTO `cgate_modules_actions` (`action_id`, `module_id`, `action_name_key`, `sort`) VALUES
('add_update', 'bikes', 'module_action_add_update', 210),
('add_update', 'commissioners', 'module_action_add_update', 114),
('add_update', 'customers', 'module_action_add_update', 1),
('add_update', 'employees', 'module_action_add_update', 130),
('add_update', 'guides', 'module_action_add_update', 126),
('add_update', 'massages', 'module_action_add_update', 112),
('add_update', 'suppliers', 'module_action_add_update', 100),
('add_update', 'tickets', 'module_action_add_update', 62),
('add_update', 'tours', 'module_action_add_update', 66),
('add_update', 'transportations', 'module_action_add_update', 122),
('delete', 'bikes', 'module_action_delete', 64),
('delete', 'commissioners', 'module_action_delete', 125),
('delete', 'customers', 'module_action_delete', 20),
('delete', 'employees', 'module_action_delete', 140),
('delete', 'guides', 'module_action_delete', 127),
('delete', 'massages', 'module_action_delete', 113),
('delete', 'suppliers', 'module_action_delete', 110),
('delete', 'tickets', 'module_action_delete', 63),
('delete', 'tours', 'module_action_delete', 67),
('delete', 'transportations', 'module_action_delete', 123),
('edit_sale', 'massages', 'module_edit_sale', 300),
('edit_sale', 'sales', 'module_edit_sale', 115),
('edit_sale', 'tickets', 'module_edit_sale', 201),
('edit_sale', 'tours', 'module_edit_sale', 200),
('edit_sale_price', 'sales', 'module_edit_sale_price', 116),
('edit_sale_price', 'tickets', 'module_edit_sale_price', 170),
('give_discount', 'sales', 'module_give_discount', 117),
('give_discount', 'tickets', 'module_give_discount', 180),
('search', 'bikes', 'module_action_search_bikes', 65),
('search', 'commissioners', 'module_action_search_commissioners', 124),
('search', 'customers', 'module_action_search_customers', 30),
('search', 'employees', 'module_action_search_employees', 150),
('search', 'guides', 'module_action_search_guides', 128),
('search', 'massages', 'module_action_search_massages', 111),
('search', 'suppliers', 'module_action_search_suppliers', 120),
('search', 'tickets', 'module_action_search_tickets', 64),
('search', 'tours', 'module_action_search_tours', 68),
('search', 'transportations', 'module_action_search_transportation', 121);

-- --------------------------------------------------------

--
-- Table structure for table `cgate_offices`
--

CREATE TABLE IF NOT EXISTS `cgate_offices` (
  `office_id` int(10) NOT NULL AUTO_INCREMENT,
  `office_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `alias_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `deleted` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`office_id`),
  KEY `deleted` (`deleted`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=7 ;

--
-- Dumping data for table `cgate_offices`
--

INSERT INTO `cgate_offices` (`office_id`, `office_name`, `alias_name`, `deleted`) VALUES
(1, 'world 1', 'world_1', 0),
(2, 'world 2', 'world_2', 0),
(3, 'world 3', 'world_3', 0),
(4, 'world 4', 'world_4', 0),
(5, 'world 5', 'world_5', 0),
(6, 'world 6', 'world_6', 0);

-- --------------------------------------------------------

--
-- Table structure for table `cgate_orders`
--

CREATE TABLE IF NOT EXISTS `cgate_orders` (
  `order_id` int(10) NOT NULL AUTO_INCREMENT,
  `sale_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `customer_id` int(10) DEFAULT NULL,
  `employee_id` int(10) DEFAULT NULL,
  `guide_id` int(11) DEFAULT NULL,
  `received_from` int(10) DEFAULT NULL COMMENT 'Received from office',
  `comment` text COLLATE utf8_unicode_ci NOT NULL,
  `show_comment_on_receipt` int(1) NOT NULL DEFAULT '0',
  `payment_type` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `deposit` decimal(10,0) DEFAULT '0',
  `cc_ref_no` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `deleted` int(1) NOT NULL DEFAULT '0',
  `suspended` int(1) NOT NULL DEFAULT '0',
  `description` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `commision_price` decimal(10,0) DEFAULT NULL,
  `commisioner_id` int(11) DEFAULT NULL,
  `category` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`order_id`),
  KEY `customer_id` (`customer_id`),
  KEY `employee_id` (`employee_id`),
  KEY `deleted` (`deleted`),
  KEY `fk_orders_tours_guides1_idx` (`guide_id`),
  KEY `fk_cgate_orders_cgate_offices1_idx` (`received_from`),
  KEY `fk_cgate_orders_cgate_commissioner` (`commisioner_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=374 ;

--
-- Dumping data for table `cgate_orders`
--

INSERT INTO `cgate_orders` (`order_id`, `sale_time`, `customer_id`, `employee_id`, `guide_id`, `received_from`, `comment`, `show_comment_on_receipt`, `payment_type`, `deposit`, `cc_ref_no`, `deleted`, `suspended`, `description`, `commision_price`, `commisioner_id`, `category`) VALUES
(1, '2013-12-26 03:42:24', NULL, 1, NULL, NULL, '', 0, NULL, NULL, '', 0, 0, NULL, NULL, NULL, ''),
(123, '2014-01-08 07:37:53', NULL, 1, NULL, NULL, '', 0, 'Cash: $15.00<br />', NULL, '', 0, 0, NULL, NULL, NULL, ''),
(124, '2014-01-08 07:44:00', NULL, 1, NULL, NULL, '', 0, 'Cash: $15.00<br />', NULL, '', 0, 0, NULL, NULL, NULL, ''),
(125, '2014-01-08 07:47:51', NULL, 1, NULL, NULL, '', 0, 'Cash: $10.00<br />', NULL, '', 0, 0, NULL, NULL, NULL, ''),
(126, '2014-01-08 07:53:25', NULL, 1, NULL, NULL, '', 0, 'Cash: $10.00<br />', NULL, '', 0, 0, NULL, NULL, NULL, ''),
(127, '2014-01-08 07:58:10', NULL, 1, NULL, NULL, '', 0, 'Cash: $10.00<br />', NULL, '', 0, 0, NULL, NULL, NULL, ''),
(128, '2014-01-08 08:04:13', NULL, 1, NULL, NULL, '', 0, 'Cash: $15.00<br />', NULL, '', 0, 0, NULL, NULL, NULL, ''),
(129, '2014-01-08 08:06:20', NULL, 1, NULL, NULL, '', 0, 'Cash: $10.00<br />', NULL, '', 0, 0, NULL, NULL, NULL, ''),
(130, '2014-01-08 08:15:53', NULL, 1, NULL, NULL, '', 0, 'Cash: $25.00<br />', NULL, '', 0, 0, NULL, NULL, NULL, ''),
(134, '2014-01-09 01:32:56', NULL, 1, NULL, NULL, '', 0, 'Cash: $20.00<br />', NULL, '', 0, 0, NULL, NULL, NULL, ''),
(135, '2014-01-09 04:03:45', NULL, 1, NULL, NULL, '', 0, 'Cash: $15.00<br />Cash: $25.00<br />', NULL, '', 0, 0, NULL, NULL, NULL, ''),
(136, '2014-01-13 03:07:35', 170, 1, NULL, NULL, '', 0, '', NULL, '', 1, 1, NULL, NULL, NULL, ''),
(137, '2014-01-13 03:09:52', 170, 1, NULL, NULL, '', 0, '', NULL, '', 1, 1, NULL, NULL, NULL, ''),
(138, '2014-01-13 03:11:55', 170, 1, NULL, NULL, '', 0, '', NULL, '', 1, 1, NULL, NULL, NULL, ''),
(139, '2014-01-13 03:12:56', 170, 1, NULL, NULL, '', 0, '', NULL, '', 1, 1, NULL, NULL, NULL, ''),
(140, '2014-01-13 03:14:32', 170, 1, NULL, NULL, '', 0, '', NULL, '', 1, 1, NULL, NULL, NULL, ''),
(187, '2014-01-18 03:07:23', NULL, 1, NULL, NULL, '', 0, 'Cash: $25.00<br />', NULL, '', 0, 0, NULL, NULL, NULL, ''),
(188, '2014-01-18 03:09:03', NULL, 1, NULL, NULL, '', 0, 'Cash: $25.00<br />', NULL, '', 0, 0, NULL, NULL, NULL, ''),
(195, '2014-01-18 04:00:12', NULL, 1, NULL, NULL, '', 0, 'Cash: $25.00<br />', NULL, '', 0, 0, NULL, NULL, NULL, ''),
(196, '2014-01-18 04:06:53', NULL, 1, NULL, NULL, '', 0, 'Cash: $15.00<br />Cash: $15.00<br />Cash: $15.00<br />Cash: $15.00<br />Cash: $15.00<br />Cash: $15.00<br />Cash: $15.00<br />Cash: $15.00<br />Cash: $15.00<br />Cash: $15.00<br />Cash: $15.00<br />Cash: $15.00<br />Cash: $15.00<br />Cash: $15.00<br />Cas', NULL, '', 0, 0, NULL, NULL, NULL, ''),
(197, '2014-01-18 04:54:10', NULL, 1, NULL, NULL, '', 0, 'Cash: $15.00<br />Cash: $15.00<br />', NULL, '', 0, 0, NULL, NULL, NULL, ''),
(198, '2014-01-18 05:02:47', NULL, 1, NULL, NULL, '', 0, 'Cash: $10.00<br />Cash: $10.00<br />Cash: $10.00<br />Cash: $10.00<br />', NULL, '', 0, 0, NULL, NULL, NULL, ''),
(199, '2014-01-18 05:08:45', NULL, 1, NULL, NULL, '', 0, 'Cash: $10.00<br />', NULL, '', 0, 0, NULL, NULL, NULL, ''),
(200, '2014-01-18 05:13:43', NULL, 1, NULL, NULL, '', 0, 'Cash: $10.00<br />', NULL, '', 0, 0, NULL, NULL, NULL, ''),
(201, '2014-01-19 15:41:46', NULL, 1, NULL, NULL, '', 0, 'Cash: $50.00<br />', NULL, '', 0, 0, NULL, NULL, NULL, ''),
(202, '2014-01-20 01:29:20', 180, 1, NULL, NULL, '', 0, 'Cash: $22.50<br />', NULL, '', 0, 0, NULL, NULL, NULL, ''),
(203, '2014-01-20 02:16:11', NULL, 1, NULL, NULL, '', 0, 'Cash: $15.00<br />Cash: $15.00<br />', NULL, '', 0, 0, NULL, NULL, NULL, ''),
(204, '2014-01-20 02:21:13', NULL, 1, NULL, NULL, '', 0, '', NULL, '', 0, 0, NULL, NULL, NULL, ''),
(205, '2014-01-20 03:48:07', NULL, 1, NULL, NULL, '', 0, 'Cash: $15.00<br />Cash: $15.00<br />Cash: -$15.00<br />', NULL, '', 0, 0, NULL, NULL, NULL, ''),
(206, '2014-01-20 04:22:02', NULL, 1, NULL, NULL, '', 0, 'Cash: $15.00<br />', NULL, '', 0, 0, NULL, NULL, NULL, ''),
(207, '2014-01-20 04:34:55', NULL, 1, NULL, NULL, '', 0, '', NULL, '', 0, 0, NULL, NULL, NULL, ''),
(208, '2014-01-20 04:46:56', NULL, 1, NULL, NULL, '', 0, '', NULL, '', 0, 0, NULL, NULL, NULL, ''),
(209, '2014-01-20 05:41:09', NULL, 1, NULL, NULL, '', 0, '', NULL, '', 0, 0, NULL, NULL, NULL, ''),
(210, '2014-01-20 05:45:58', NULL, 1, NULL, NULL, '', 0, '', NULL, '', 0, 0, NULL, NULL, NULL, ''),
(211, '2014-01-20 05:50:36', NULL, 1, NULL, NULL, '', 0, '', NULL, '', 0, 0, NULL, NULL, NULL, ''),
(212, '2014-01-20 05:52:45', NULL, 1, NULL, NULL, '', 0, '', NULL, '', 0, 0, NULL, NULL, NULL, ''),
(213, '2014-01-21 04:57:52', NULL, 1, NULL, NULL, '', 0, '', NULL, '', 0, 0, NULL, NULL, NULL, ''),
(214, '2014-01-23 01:15:16', NULL, 1, NULL, NULL, '', 0, '', NULL, '', 0, 0, NULL, NULL, NULL, ''),
(215, '2014-01-23 03:09:42', 170, 1, NULL, NULL, '', 0, '', NULL, '', 0, 0, NULL, '20', 3, ''),
(216, '2014-01-23 03:20:20', NULL, 1, NULL, NULL, '', 0, '', NULL, '', 0, 0, NULL, '0', 3, ''),
(217, '2014-01-23 03:25:35', 180, 1, NULL, NULL, '', 0, '', NULL, '', 0, 0, NULL, '0', 1, ''),
(218, '2014-01-23 03:32:36', NULL, 1, NULL, NULL, '', 0, 'Cash: $18.00<br />', NULL, '', 0, 0, NULL, '0', NULL, ''),
(219, '2014-01-23 03:37:35', NULL, 1, NULL, NULL, '', 0, 'Cash: $11.64<br />', NULL, '', 0, 0, NULL, '3', NULL, ''),
(220, '2014-01-23 09:57:28', 180, 1, NULL, NULL, '', 0, 'Cash: $31.00<br />', NULL, '', 0, 0, NULL, '2', 3, ''),
(221, '2014-01-23 15:31:25', 180, 1, NULL, NULL, '', 0, 'Cash: $9.00<br />', NULL, '', 0, 0, NULL, '1', 2, ''),
(222, '2014-01-24 01:16:57', 180, 1, NULL, NULL, '', 0, 'Cash: $8.10<br />', NULL, '', 0, 0, NULL, '1', 2, ''),
(223, '2014-01-25 06:50:38', 180, 1, NULL, NULL, '', 0, 'Cash: $9.00<br />', NULL, '', 0, 0, NULL, '1', 1, ''),
(224, '2014-01-25 15:39:59', 93, 1, NULL, NULL, '', 0, 'Cash: $9.50<br />', NULL, '', 0, 0, NULL, '1', 3, ''),
(225, '2014-01-25 16:01:35', 93, 1, NULL, NULL, '', 0, 'Cash: $9.50<br />', NULL, '', 0, 0, NULL, '2', 1, ''),
(226, '2014-01-25 16:13:40', 93, 1, NULL, NULL, '', 0, 'Cash: $9.00<br />Cash: -$0.45<br />', NULL, '', 0, 0, NULL, '0', NULL, ''),
(227, '2014-01-28 07:26:51', 93, 1, NULL, NULL, '', 0, 'Cash: $16.30<br />', NULL, '', 0, 0, NULL, '2', 2, ''),
(235, '2014-01-31 03:06:56', NULL, 1, NULL, NULL, '', 0, 'Cash: $11.00<br />', NULL, '', 0, 0, NULL, '0', NULL, ''),
(236, '2014-01-31 03:37:34', NULL, 1, 2, NULL, '', 0, 'Cash: $26.00<br />', NULL, '', 0, 0, NULL, '0', NULL, ''),
(244, '2014-01-31 04:14:44', NULL, 1, 2, NULL, '', 0, 'Cash: $45.00<br />', NULL, '', 0, 0, NULL, '0', NULL, ''),
(245, '2014-01-31 09:09:16', 191, 1, 2, NULL, '', 0, 'Cash: $12.00<br />', NULL, '', 0, 0, NULL, '1', 2, ''),
(246, '2014-01-31 09:21:21', 181, 1, 2, NULL, '', 0, 'Cash: $12.00<br />', NULL, '', 0, 0, NULL, '3', 7, ''),
(247, '2014-02-01 01:20:59', 192, 1, 3, NULL, '', 0, 'Cash: $45.00<br />', NULL, '', 0, 0, NULL, '2', 7, ''),
(248, '2014-02-01 01:39:43', 181, 1, 1, NULL, '', 0, 'Cash: $45.00<br />', NULL, '', 0, 0, NULL, '2', 7, ''),
(249, '2014-02-01 01:41:04', 181, 1, 1, NULL, '', 0, 'Cash: $45.00<br />', NULL, '', 0, 0, NULL, '2', 7, ''),
(250, '2014-02-01 02:29:30', NULL, 1, NULL, NULL, '', 0, 'Cash: $12.00<br />', NULL, '', 0, 0, NULL, '0', NULL, ''),
(251, '2014-02-01 03:03:34', 180, 1, NULL, NULL, '', 0, 'Cash: $25.00<br />', NULL, '', 0, 0, NULL, '0', NULL, ''),
(252, '2014-02-01 03:05:12', 181, 1, NULL, NULL, '', 0, 'Cash: $15.00<br />', NULL, '', 0, 0, NULL, '0', NULL, ''),
(253, '2014-02-01 03:10:50', 191, 1, 1, NULL, '', 0, 'Cash: $15.00<br />', NULL, '', 0, 0, NULL, '3', 2, ''),
(254, '2014-02-03 01:59:13', NULL, 1, NULL, NULL, '', 1, 'Cash: $26.00<br />', NULL, '', 0, 0, NULL, '0', NULL, ''),
(255, '2014-02-03 03:04:04', NULL, 1, NULL, NULL, '', 1, 'Cash: $26.00<br />', NULL, '', 0, 0, NULL, '0', NULL, ''),
(256, '2014-02-03 03:16:22', NULL, 1, NULL, NULL, '', 1, 'Cash: $40.00<br />', NULL, '', 0, 0, NULL, '0', NULL, ''),
(257, '2014-02-09 17:00:00', NULL, 1, NULL, NULL, '', 1, 'Cash: $30.00<br />', NULL, '', 0, 0, NULL, '0', NULL, ''),
(258, '2014-02-13 17:00:00', 192, 1, NULL, NULL, '', 0, 'Cash: $9.00<br />Cash: $9.00<br />', NULL, '', 0, 0, NULL, '2', 7, ''),
(259, '2014-02-04 06:39:21', NULL, 1, NULL, NULL, '', 0, 'Cash: $5.00<br />', NULL, '', 0, 0, NULL, '0', NULL, ''),
(301, '2014-02-05 03:57:39', NULL, 1, NULL, NULL, '', 0, 'Cash: $8.00<br />', NULL, '', 0, 0, NULL, '0', NULL, ''),
(311, '2014-02-05 06:50:18', NULL, 1, NULL, NULL, '', 0, 'Cash: $7.00<br />', NULL, '', 0, 0, NULL, '0', NULL, ''),
(313, '2014-02-05 07:06:06', NULL, 1, NULL, NULL, '', 0, 'Cash: $11.00<br />', NULL, '', 0, 0, NULL, '0', NULL, ''),
(314, '2014-02-05 07:07:38', NULL, 1, NULL, NULL, '', 0, 'Cash: $12.00<br />', NULL, '', 0, 0, NULL, '0', NULL, ''),
(315, '2014-02-05 07:48:52', 161, 1, 1, NULL, '', 0, 'Cash: $12.00<br />', NULL, '', 0, 0, NULL, '2', 7, ''),
(316, '2014-02-05 07:55:03', NULL, 1, NULL, NULL, '', 0, 'Cash: $30.00<br />', NULL, '', 0, 0, NULL, '0', NULL, ''),
(317, '2014-02-06 17:00:00', 169, 1, NULL, NULL, '', 0, 'Cash: $11.00<br />Cash: $1.00<br />Cash: $2.00<br />', NULL, '', 0, 0, NULL, '1', 2, ''),
(318, '2014-02-06 08:10:47', NULL, 1, NULL, NULL, '', 0, 'Cash: $14.00<br />', NULL, '', 0, 0, NULL, '0', NULL, ''),
(319, '2014-02-12 17:00:00', 93, 1, NULL, NULL, '', 0, 'Cash: $16.00<br />', NULL, '', 0, 0, NULL, '1', 2, ''),
(320, '2014-02-07 16:40:49', NULL, 1, NULL, NULL, '', 0, 'Cash: $15.00<br />', NULL, '', 0, 0, NULL, '0', NULL, ''),
(321, '2014-02-07 17:29:35', NULL, 1, NULL, NULL, '', 0, 'Cash: $11.00<br />', NULL, '', 0, 0, NULL, '0', NULL, ''),
(328, '2014-02-07 17:56:09', NULL, 1, NULL, NULL, '', 0, 'Cash: $15.00<br />', NULL, '', 0, 0, NULL, '0', NULL, ''),
(329, '2014-02-07 17:56:48', NULL, 1, NULL, NULL, '', 0, 'Cash: $15.00<br />Cash: $0.00<br />', NULL, '', 0, 0, NULL, '0', NULL, ''),
(330, '2014-02-07 17:58:57', NULL, 1, NULL, NULL, '', 0, 'Cash: $8.00<br />Cash: $9.00<br />', NULL, '', 0, 0, NULL, '0', NULL, ''),
(331, '2014-02-07 18:04:07', 2, 1, NULL, NULL, '', 0, 'Cash: $26.00<br />', NULL, '', 0, 0, NULL, '0', NULL, ''),
(338, '2014-02-08 13:43:04', NULL, 1, NULL, NULL, '', 0, 'Cash: $43.00<br />', NULL, '', 0, 0, NULL, '0', NULL, ''),
(339, '2014-02-08 17:00:19', 169, 1, NULL, NULL, '', 0, 'Cash: $43.00<br />', NULL, '', 0, 0, NULL, '1', 2, ''),
(340, '2014-02-09 16:58:51', NULL, 1, NULL, NULL, '', 0, 'Cash: $15.00<br />', NULL, '', 0, 0, NULL, '0', NULL, ''),
(341, '2014-02-10 03:38:07', NULL, 1, NULL, NULL, '', 0, 'Cash: $26.00<br />', NULL, '', 0, 0, NULL, '0', NULL, ''),
(342, '2014-02-11 08:00:00', 170, 1, NULL, NULL, '', 0, 'Cash: $136.00<br />', '0', '', 0, 0, NULL, '0', 15, ''),
(343, '2014-02-12 03:12:45', NULL, 1, NULL, NULL, '', 0, 'Cash: $61.00<br />', NULL, '', 0, 0, NULL, '0', NULL, ''),
(344, '2014-02-17 17:00:00', NULL, 1, NULL, NULL, 'Hello world, what are you doing?\n', 0, 'Check: $136.00<br />Credit Card: $0.00<br />', NULL, '', 0, 0, NULL, '0', 2, ''),
(345, '2014-02-14 04:07:44', 2, 1, NULL, NULL, 'Hello world', 0, 'Check: $10.00<br />Cash: $126.00<br />', NULL, '', 0, 0, NULL, '2', NULL, ''),
(346, '2014-02-24 07:27:48', NULL, 1, NULL, NULL, '', 0, 'Cash: $11.00<br />', '0', '', 0, 0, NULL, '0', NULL, ''),
(347, '2014-02-24 07:28:51', NULL, 1, NULL, NULL, '', 0, 'Cash: $11.00<br />Cash: $126.00<br />', '0', '', 0, 0, NULL, '0', NULL, ''),
(348, '2014-02-24 07:30:18', NULL, 1, NULL, NULL, '', 0, 'Cash: $11.00<br />Cash: $126.00<br />', '0', '', 0, 0, NULL, '0', NULL, ''),
(349, '2014-02-25 01:09:22', NULL, 1, NULL, NULL, '', 0, 'Cash: $11.00<br />', '0', '', 0, 0, NULL, '0', NULL, ''),
(350, '2014-02-25 01:17:35', NULL, 1, NULL, NULL, '', 0, 'Cash: $11.00<br />', '0', '', 0, 0, NULL, '0', NULL, ''),
(351, '2014-02-25 01:31:58', NULL, 1, NULL, NULL, '', 0, 'Cash: $12.00<br />', '0', '', 0, 0, NULL, '0', NULL, ''),
(352, '2014-02-04 17:00:00', 207, 1, NULL, NULL, 'This is my testing on date and time departure', 0, 'Cash: $15.00<br />Cash: $11.00<br />', '15', '', 0, 0, NULL, '0', NULL, ''),
(353, '2014-02-25 02:27:27', NULL, 1, NULL, NULL, '', 0, 'Cash: $126.00<br />', '100', '', 0, 0, NULL, '0', NULL, ''),
(354, '2014-03-06 19:19:37', 168, 1, NULL, NULL, '', 0, 'Cash: $12.00<br />', '0', '', 0, 1, NULL, '0', NULL, 'tickets'),
(355, '2014-03-07 07:26:57', NULL, 1, NULL, NULL, '', 0, 'Cash: $11.00<br />', '0', '', 0, 0, NULL, '0', NULL, 'tickets'),
(356, '2014-03-07 07:52:09', 93, 1, NULL, NULL, 'Hello world for 356 id', 0, 'Cash: $136.00<br />', '0', '', 0, 0, NULL, '5', 2, 'tickets'),
(357, '2014-03-07 10:19:23', 93, 1, NULL, NULL, 'Hello world for 356 id', 0, 'Cash: $136.00<br />', '0', '', 0, 0, NULL, '5', 2, 'tickets'),
(358, '2014-03-07 13:12:14', 93, 1, NULL, NULL, 'Hello world for 356 id', 0, 'Cash: $136.00<br />', '25', '', 0, 0, NULL, '5', 2, 'tickets'),
(359, '2014-03-08 10:48:19', NULL, 1, NULL, NULL, '', 0, 'Cash: $126.00<br />', '0', '', 0, 0, NULL, '0', NULL, 'tickets'),
(360, '2014-03-11 08:47:27', NULL, 1, NULL, NULL, '', 0, 'Cash: $10.00<br />', '0', '', 0, 0, NULL, '0', NULL, 'tickets'),
(361, '2014-03-13 03:36:13', NULL, 1, NULL, NULL, '', 0, 'Cash: $25.00<br />', '0', '', 0, 0, NULL, '0', NULL, 'tickets'),
(362, '2014-03-14 18:36:18', NULL, 1, NULL, NULL, '', 0, 'Cash: $35.00<br />', '0', '', 0, 0, NULL, '0', NULL, ''),
(364, '2014-03-15 16:55:15', NULL, 1, NULL, NULL, '', 0, 'Cash: $11.00<br />', '0', '', 0, 0, NULL, '0', NULL, 'tickets'),
(365, '2014-03-15 16:56:11', NULL, 236, NULL, NULL, '', 0, 'Cash: $11.00<br />', '0', '', 0, 0, NULL, '0', NULL, 'tickets'),
(366, '2014-03-15 16:59:20', NULL, 236, NULL, NULL, '', 0, 'Cash: $12.00<br />', '0', '', 0, 0, NULL, '0', NULL, 'tickets'),
(367, '2014-03-15 17:50:10', NULL, 236, NULL, NULL, '', 0, 'Cash: $11.00<br />Cash: $11.00<br />', '0', '', 0, 0, NULL, '0', NULL, 'tickets'),
(368, '2014-03-15 18:14:29', NULL, 240, NULL, NULL, '', 0, 'Cash: $11.00<br />Cash: $11.00<br />', '0', '', 0, 0, NULL, '0', NULL, 'tickets'),
(369, '2014-03-15 21:03:42', 2, 1, NULL, NULL, '', 0, 'Cash: $126.00<br />', '0', '', 0, 0, NULL, '5', 2, 'tickets'),
(370, '2014-03-15 21:06:17', 123, 1, NULL, NULL, '', 0, 'Cash: $126.00<br />', '0', '', 0, 0, NULL, '5', 7, 'tickets'),
(371, '2014-03-16 07:04:09', NULL, 1, NULL, NULL, '', 0, 'Cash: $30.00<br />', '0', '', 0, 0, NULL, '0', NULL, 'tickets'),
(372, '2014-03-17 01:20:07', NULL, 1, NULL, NULL, '', 0, 'Cash: $15.00<br />', '0', '', 0, 0, NULL, '0', NULL, 'massages'),
(373, '2014-03-17 01:23:19', NULL, 1, NULL, NULL, '', 0, 'Cash: $15.00<br />', '0', '', 0, 1, NULL, '0', NULL, 'massages');

-- --------------------------------------------------------

--
-- Table structure for table `cgate_orders_item_kits`
--

CREATE TABLE IF NOT EXISTS `cgate_orders_item_kits` (
  `sale_id` int(10) NOT NULL DEFAULT '0',
  `item_kitID` int(10) NOT NULL DEFAULT '0' COMMENT 'Order as group on each items',
  `description` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `line` int(3) NOT NULL DEFAULT '0',
  `quantity_purchased` decimal(10,0) NOT NULL DEFAULT '0',
  `item_kit_cost_price` decimal(10,0) NOT NULL,
  `item_kit_unit_price` decimal(10,0) NOT NULL,
  `discount_percent` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`sale_id`,`line`,`item_kitID`),
  KEY `item_kit_id` (`item_kitID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `cgate_orders_item_kits`
--

INSERT INTO `cgate_orders_item_kits` (`sale_id`, `item_kitID`, `description`, `line`, `quantity_purchased`, `item_kit_cost_price`, `item_kit_unit_price`, `discount_percent`) VALUES
(1, 1, NULL, 1, '1', '12', '11', 10),
(123, 2, 'We add mroe one...', 1, '1', '25', '15', 0),
(124, 2, 'We add mroe one...', 1, '1', '25', '15', 0),
(125, 1, 'We include.....', 1, '1', '15', '10', 0),
(126, 1, 'We include.....', 1, '1', '15', '10', 0),
(127, 1, 'We include.....', 1, '1', '15', '10', 0),
(128, 2, 'We add mroe one...', 1, '1', '25', '15', 0),
(129, 1, 'We include.....', 1, '1', '15', '10', 0),
(130, 3, 'hhhh', 1, '1', '30', '25', 0),
(135, 2, 'We add mroe one...', 1, '1', '25', '15', 0),
(135, 3, 'hhhh', 2, '1', '30', '25', 0),
(136, 3, 'hhhh', 1, '1', '30', '25', 0),
(137, 3, 'hhhh', 1, '1', '30', '25', 0),
(138, 3, 'hhhh', 1, '1', '30', '25', 0),
(139, 3, 'hhhh', 1, '1', '30', '25', 0),
(140, 3, 'hhhh', 1, '1', '30', '25', 0),
(187, 3, 'hhhh', 1, '1', '30', '25', 0),
(188, 3, 'hhhh', 1, '1', '30', '25', 0),
(195, 3, 'hhhh', 1, '1', '30', '25', 0),
(196, 2, 'We add mroe one...', 1, '1', '25', '15', 0),
(197, 2, 'We add mroe one...', 1, '1', '25', '15', 0),
(198, 1, 'We include.....', 1, '1', '15', '10', 0),
(199, 1, 'We include.....', 1, '1', '15', '10', 0),
(200, 1, 'We include.....', 1, '1', '15', '10', 0),
(201, 3, 'hhhh', 1, '2', '30', '25', 0),
(202, 3, 'hhhh', 1, '1', '30', '25', 10),
(203, 2, 'We add mroe one...', 1, '1', '25', '15', 0),
(204, 3, 'hhhh', 1, '1', '30', '25', 0),
(204, 2, 'We add mroe one...', 2, '1', '25', '15', 0),
(204, 1, 'We include.....', 3, '1', '15', '10', 0),
(205, 2, 'We add mroe one...', 1, '1', '25', '15', 0),
(206, 2, 'We add mroe one...', 1, '1', '25', '15', 0),
(207, 3, 'hhhh', 1, '1', '30', '25', 0),
(208, 3, 'hhhh', 1, '1', '30', '25', 0),
(209, 2, 'We add mroe one...', 1, '1', '25', '15', 0),
(210, 1, 'We include.....', 1, '1', '15', '10', 0),
(211, 2, 'We add mroe one...', 1, '1', '25', '15', 0),
(212, 3, 'hhhh', 1, '1', '30', '25', 0),
(216, 1, 'We include.....', 1, '1', '15', '10', 10);

-- --------------------------------------------------------

--
-- Table structure for table `cgate_people`
--

CREATE TABLE IF NOT EXISTS `cgate_people` (
  `first_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `last_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `phone_number` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `address_1` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `address_2` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `city` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `state` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `zip` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `country` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `comments` text COLLATE utf8_unicode_ci NOT NULL,
  `person_id` int(10) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`person_id`),
  KEY `first_name` (`first_name`),
  KEY `last_name` (`last_name`),
  KEY `email` (`email`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=244 ;

--
-- Dumping data for table `cgate_people`
--

INSERT INTO `cgate_people` (`first_name`, `last_name`, `phone_number`, `email`, `address_1`, `address_2`, `city`, `state`, `zip`, `country`, `comments`, `person_id`) VALUES
('youlay', 'hong', '097 64 25 551', 'youlay.hong@gmail.com', 'Kandal', 'Phnom Penh', 'Phnom Penh', 'Kandal', '99999999999', 'Cambodia', '', 1),
('sreychen', 'sok', '0979586953', 'sreychen.sok@gmail.com', 'Tou rid  ', 'Tak Sin', 'Phnom Penh', 'Takeo', '', 'Cambodia', 'this is my comments', 2),
('sreychen', 'sok', '', 'sreychen.sok@gmail.com', '', '', '', '', '', '', '', 3),
('yuly', 'hong', '', '', '', '', '', '', '', '', '', 4),
('Seesawseen', 'Rice', '097 86 95 95', 'seerice@gmail.com', 'Totita', 'Korika', 'Tamdeo', 'Tikeo', 'ziper', 'Takdoit', 'Taku good nas', 5),
('chhing', 'hem', '0972792217', 'chhing@gmail.com', 'Takeo', 'DounPher', 'Takeo', 'PP', 'zip', 'cambodia', 'She is a supplier', 6),
('chantrea', 'sok', '0972793211', 'chantrea@gmail.com', 'Pursat', 'Pursat', 'Pursat', 'PP', '+885', 'Cambodai', 'Sokry Kampong Thom', 7),
('sina', 'ea', '0972792219', 'sina.ea9@gmail.com', 'Takeo', 'pp', 'toul kork', 'Cambodia', '+885', 'Cambodia', '0', 25),
('pisith', 'yeen', '0972792218', 'pisith@gmail.com', 'BB', 'BB', 'BB', 'BB', '+885', 'Cambodia', '0', 29),
('sreyny', 'na', '0972792220', 'sreyny@gmail.com', 'kompong cham', 'pp', 'toul kork', 'Cambodia', '+888', 'Cambodia', '0', 30),
('thida', 'pen', '0972792212', 'thidapen111@gmail.com', 'kompong cham', 'BB', 'toul kork', 'Cambodia', '+888', 'Cambodia', '0', 31),
('sreyna', 'ny', '012345678', 'sreyna@gmail.com', 'Takeo', 'Takeo', 'Takeo', 'Takeo', '+885', 'Cambodia', 'Hello CCC Company', 32),
('sreychen', 'sok', '098123456', 'sreychen@gmail.com', 'Takeo', 'pp', 'toul kork', 'Takeo', '+885', 'Cambodia', '0', 33),
('lyhong', 'pon', '0972792214', 'lyhong@gmail.com', 'Takeo', 'pp', 'pp', 'pp', '+888', 'Cambodia', '0', 35),
('add', 'bdd', '0971234567', 'add@gmail.com', 'pp', 'pp', 'pp', 'pp', '+885', 'Cambodia', '0', 36),
('heom', 'sodaly', '0978675456', 'sodaly@gmail.com', '0', '0', '0', '0', '0', '0', '0', 37),
('lay', 'long', '098767876', 'lay@gmail.com', 'pp', 'pp', 'pp', 'pp', '+888', 'Cambodia', 'Hello comments', 38),
('dane', 'chen', '098989898', 'dana@gmail.com', 'pp', 'pp', 'pp', 'pp', '+888', 'Cambodia', '0', 39),
('nung', 'chan', '098989878', 'nong@gmail.com', 'pp', 'pp', 'pp', 'pp', '+888', 'Cambodia', '0', 40),
('chhingchhing', 'hemchhing', '0972792219', 'ching88@gmail.com', 'pp', 'pp', 'pp', 'pp', '+885', 'Cambodia', 'Hello', 41),
('chhing', 'hem', '', '', '', '', '', '', '', '', '', 42),
('cheko', 'hem', '0977374664', 'cheko@gmail.com', 'Takeo', 'Takeo', 'Takeo', 'Takeo', '+885', 'Cambodia', 'Comment', 43),
('natra', 'nan', '0326363800', 'natra@gmail.com', 'us', 'us', 'us', 'us', '+885', 'us', 'near us', 45),
('kaeo', 'kaka', '0972792219', 'ka@gmail.com', 'Takeo', 'pp', 'toul kork', 'Cambodia', '+885', 'Cambodia', 'ff', 49),
('lolo', 'lan', '0972792219', 'lo@gmail.com', 'pp', 'pp', 'pp', 'pp', '+885', 'Cambodia', 'comment', 50),
('konghong', 'lay', '0972792219', 'kong@gmail.com', 'pp', 'pp', 'pp', 'pp', '+885', 'Cambodia', 'Cambodia', 51),
('coney', 'tika', '0972792219', 'coney@gmail.com', 'Takeo', 'BB', 'pp', 'Cambodia', '+888', 'Cambodia', '0', 82),
('sreynak', 'chet', '0972792219', 'sreynak.chet@gmail.com', 'Takeo', 'pp', 'toul kork', 'BB', '+888', 'Cambodia', '0', 86),
('dda', 'add', '0972792219', 'add@gmaiol.com', 'Kompong thom', 'BB', 'pp', 'BB', '+885', 'Cambodia', '0', 87),
('xmak', 'hanm', '', 'xmak@gmail.com', '', '', '', '', '', '', '0', 88),
('hh', 'hh', '0972792219', 'hh@gmail.com', 'kompong cham', 'BB', 'toul kork', 'Cambodia', '+888', 'Cambodia', '0', 89),
('ccing', 'cccing', '0972792218', 'ccc', 'kompong cham', 'pp v', 'BB b', 'BB b', '+885', 'Cambodia', '0', 90),
('dds can', 'dding', '0972792219', 'chhing99@gmail.com', 'Takeo', 'pp', 'toul kork', 'BB', '+888', 'Cambodia', '', 91),
('reaksmey', 'run', '0979898654', 'smey@gmail.com', 'svay reing', 'pp', 'toul kork', 'Svay reing', '+885', 'Cambodia', '', 93),
('cc', 'ccc', '0972792218', 'ccc', 'kompong cham', 'pp v', 'BB b', 'BB b', '+885', 'Cambodia', '0', 102),
('lanak', 'lunn', '098767687', 'lanak@gmail.com', 'Siem Reap', 'pp', 'toul kork', 'Cambodia', '+888', 'Cambodia', '', 123),
('gala', 'dinner', '0972792219', 'gala@gmail.com', 'Takeo', 'pp', 'toul kork', 'Cambodia', '+885', 'Cambodia', '', 124),
('dinner', 'gala', '', 'dinner@gmail.com', '', '', '', '', '', '', '', 125),
('newCustomer', 'customer', '972792219', 'customer@gmail.com', 'Takeo', 'pp', 'toul kork', 'Cambodia', '888', 'Cambodia', '', 161),
('a1', 'dd', '972792219', 'chhing99@gmail.com', 'Takeo', 'pp', 'toul kork', 'BB', '888', 'Cambodia', '', 162),
('a2', 'dinner', '972792219', 'gala@gmail.com', 'Takeo', 'pp', 'toul kork', 'Cambodia', '885', 'Cambodia', '', 163),
('a3', 'gala', '', 'dinner@gmail.com', '', '', '', '', '', '', '', 164),
('chhing', 'hem', '972792217', 'chhing99@gmail.com', 'Takeo', 'pp', 'toul kork', 'Cambodia', '888', 'Cambodia', 'ffffffffffffffff ffffffff', 165),
('a4', 'hong', '097 64 25 551', 'youlay.hong@gmail.com', 'Kandal', 'Phnom Penh', 'Phnom Penh', 'Kandal', '99999999999', 'Cambodia', '', 166),
('a5', 'lunn', '98767687', 'lanak@gmail.com', 'Siem Reap', 'pp', 'toul kork', 'Cambodia', '888', 'Cambodia', '', 167),
('a6', 'run', '979898654', 'smey@gmail.com', 'svay reing', 'pp', 'toul kork', 'Svay reing', '885', 'Cambodia', '', 168),
('sokry1', 'sat', '972792217', 'sokry.sat@gmail.com', 'Kompong thom', 'pp', 'toul kork', 'pp', '88', 'Cambodia', 'Hello sokry', 169),
('Sreychen', 'chea', '', 'sreychen.chea@gmail.com', '', '', '', '', '', '', '', 170),
('channak', 'hem', '', '', '', '', '', '', '', '', '', 171),
('Dara', 'Chan', '', '', '', '', '', '', '', '', '', 172),
('Dara', 'Chan', '', '', '', '', '', '', '', '', '', 173),
('bopha', 'chan', '', 'bopha@gmail.com', '', '', '', '', '', '', '', 174),
('Dara', 'Chan', '', 'dara1.chan@gmail.com', '', '', '', '', '', '', '', 175),
('Darau', 'Chan', '', '', '', '', '', '', '', '', '', 176),
('Dara', 'Chan', '', '', '', '', '', '', '', '', '', 177),
('Dara', 'Chan', '', '', '', '', '', '', '', '', '', 178),
('daya', 'han', '', '', '', '', '', '', '', '', '', 179),
('reasmy', 'trouk', '', '', '', '', '', '', '', '', '', 180),
('nara', 'ram', '', '', '', '', '', '', '', '', '', 181),
('daya', 'han', '', '', '', '', '', '', '', '', '', 182),
('daya', 'han', '', 'daya.han@gmail.com', '', '', '', '', '', '', '', 183),
('Dara', 'Chan', '', '', '', '', '', '', '', '', '', 184),
('Dara', 'Chan', '', '', '', '', '', '', '', '', '', 185),
('naroung', 'none', '', '', '', '', '', '', '', '', '', 186),
('naroung', 'none', '', '', '', '', '', '', '', '', '', 187),
('Dara', 'Chan', '', '', '', '', '', '', '', '', '', 188),
('Dara', 'Chan', '', '', '', '', '', '', '', '', '', 189),
('thavy', 'piv', '', '', '', '', '', '', '', '', '', 190),
('rama', 'math', '', '', '', '', '', '', '', '', '', 191),
('ramay', 'run', '', '', '', '', '', '', '', '', '', 192),
('hii', 'helo', '', 'hi.heloo@gmail.com', '', '', '', '', '', '', '', 193),
('naga', 'nun', '', '', '', '', '', '', '', '', '', 194),
('candy', 'him', '', '', '', '', '', '', '', '', '', 195),
('yan', 'dang', '', '', '', '', '', '', '', '', '', 196),
('laa', 'ley', '', '', '', '', '', '', '', '', '', 197),
('la', 'long', '', '', '', '', '', '', '', '', '', 198),
('samphous', 'yan', '', '', '', '', '', '', '', '', '', 206),
('channa', 'thorn', '', '', '', '', '', '', '', '', '', 207),
('lovely', 'lucky', '', '', '', '', '', '', '', '', '', 214),
('bopha', 'soun', '', '', '', '', '', '', '', '', '', 217),
('channa', 'long', '', '', '', '', '', '', '', '', '', 218),
('ja', 'jan', '', 'ja@gmail.com', '', '', '', '', '', '', '', 219),
('leng', 'long', '', '', '', '', '', '', '', '', '', 220),
('loung', 'lin', '', '', '', '', '', '', '', '', '', 221),
('lyly', 'long', '', 'lyly.long@gmail.com', '', '', '', '', '', '', '', 222),
('hunnak', 'nang', '', '', '', '', '', '', '', '', '', 223),
('t', 't', '', '', '', '', '', '', '', '', '', 224),
('zcehn', 'zhan', '0978675456', 'zcehn@gmail.com', '', '', '', '', '', '', '', 225),
('ka', 'ka', '', '', '', '', '', '', '', '', '', 226),
('ka', 'ka', '', '', '', '', '', '', '', '', '', 227),
('ka', 'ka', '', '', '', '', '', '', '', '', '', 228),
('ka', 'ka', '', '', '', '', '', '', '', '', '', 229),
('ka', 'ka', '', '', '', '', '', '', '', '', '', 230),
('kan', 'khan', '', '', '', '', '', '', '', '', '', 231),
('bopha', 'chan', '', 'bopha@gmail.com', '', '', '', '', '', '', '', 232),
('channa', 'long', '', '', '', '', '', '', '', '', '', 233),
('ttggg', 'tttgggg', '', '', '', '', '', '', '', '', '', 235),
('channa', 'hung', '', '', '', '', '', '', '', '', '', 236),
('saorin', 'phan', '', '', '', '', '', '', '', '', '', 240),
('sophan', 'ea', '', '', '', '', '', '', '', '', '', 241),
('aace', 'ged', '092123430', 'get@mgail.com', '', '', '', '', '', '', '0', 243);

-- --------------------------------------------------------

--
-- Table structure for table `cgate_permissions`
--

CREATE TABLE IF NOT EXISTS `cgate_permissions` (
  `office_id` int(10) NOT NULL,
  `person_id` int(10) NOT NULL,
  `module_id` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`office_id`,`person_id`,`module_id`),
  KEY `person_id` (`person_id`),
  KEY `fk_cgate_permissions_cgate_offices1_idx` (`office_id`),
  KEY `fk_permissions_cgate_modules` (`module_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `cgate_permissions`
--

INSERT INTO `cgate_permissions` (`office_id`, `person_id`, `module_id`) VALUES
(1, 1, 'bikes'),
(1, 1, 'commissioners'),
(1, 1, 'config'),
(1, 1, 'customers'),
(1, 1, 'employees'),
(1, 1, 'guides'),
(1, 1, 'massages'),
(1, 1, 'reports'),
(1, 1, 'sales'),
(1, 1, 'suppliers'),
(1, 1, 'tickets'),
(1, 1, 'tours'),
(1, 1, 'transportations'),
(2, 1, 'bikes'),
(2, 1, 'commissioners'),
(2, 1, 'config'),
(2, 1, 'customers'),
(2, 1, 'employees'),
(2, 1, 'guides'),
(2, 1, 'massages'),
(2, 1, 'reports'),
(2, 1, 'suppliers'),
(2, 1, 'tickets'),
(2, 1, 'tours'),
(2, 1, 'transportations'),
(3, 1, 'bikes'),
(3, 1, 'commissioners'),
(3, 1, 'config'),
(3, 1, 'customers'),
(3, 1, 'employees'),
(3, 1, 'guides'),
(3, 1, 'massages'),
(3, 1, 'reports'),
(3, 1, 'suppliers'),
(3, 1, 'tickets'),
(3, 1, 'tours'),
(3, 1, 'transportations'),
(4, 1, 'bikes'),
(4, 1, 'commissioners'),
(4, 1, 'config'),
(4, 1, 'customers'),
(4, 1, 'employees'),
(4, 1, 'guides'),
(4, 1, 'massages'),
(4, 1, 'reports'),
(4, 1, 'suppliers'),
(4, 1, 'tickets'),
(4, 1, 'tours'),
(4, 1, 'transportations'),
(1, 214, 'customers'),
(1, 214, 'suppliers'),
(2, 214, 'customers'),
(2, 214, 'suppliers'),
(1, 218, 'sales'),
(1, 218, 'tickets'),
(2, 218, 'sales'),
(2, 218, 'tickets'),
(3, 218, 'sales'),
(3, 218, 'tickets'),
(1, 219, 'customers'),
(2, 219, 'customers'),
(1, 222, 'customers'),
(1, 222, 'suppliers'),
(2, 222, 'customers'),
(2, 222, 'suppliers'),
(1, 223, 'customers'),
(1, 223, 'suppliers'),
(2, 223, 'customers'),
(2, 223, 'suppliers'),
(1, 225, 'customers'),
(1, 225, 'suppliers'),
(2, 225, 'customers'),
(2, 225, 'suppliers'),
(1, 233, 'customers'),
(2, 233, 'customers'),
(1, 236, 'employees'),
(1, 236, 'tickets'),
(1, 240, 'tickets');

-- --------------------------------------------------------

--
-- Table structure for table `cgate_permissions_actions`
--

CREATE TABLE IF NOT EXISTS `cgate_permissions_actions` (
  `module_id` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `person_id` int(11) NOT NULL,
  `action_id` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`module_id`,`person_id`,`action_id`),
  KEY `phppos_permissions_actions_ibfk_2` (`person_id`),
  KEY `phppos_permissions_actions_ibfk_3` (`action_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `cgate_permissions_actions`
--

INSERT INTO `cgate_permissions_actions` (`module_id`, `person_id`, `action_id`) VALUES
('bikes', 1, 'add_update'),
('bikes', 1, 'delete'),
('bikes', 1, 'search'),
('commissioners', 1, 'add_update'),
('commissioners', 1, 'delete'),
('commissioners', 1, 'search'),
('customers', 1, 'add_update'),
('customers', 1, 'delete'),
('customers', 1, 'search'),
('employees', 1, 'add_update'),
('employees', 1, 'delete'),
('employees', 1, 'search'),
('guides', 1, 'add_update'),
('guides', 1, 'delete'),
('guides', 1, 'search'),
('massages', 1, 'add_update'),
('massages', 1, 'delete'),
('massages', 1, 'edit_sale'),
('massages', 1, 'search'),
('sales', 1, 'edit_sale'),
('sales', 1, 'edit_sale_price'),
('sales', 1, 'give_discount'),
('suppliers', 1, 'add_update'),
('suppliers', 1, 'delete'),
('suppliers', 1, 'search'),
('tickets', 1, 'add_update'),
('tickets', 1, 'delete'),
('tickets', 1, 'edit_sale'),
('tickets', 1, 'edit_sale_price'),
('tickets', 1, 'give_discount'),
('tickets', 1, 'search'),
('tours', 1, 'add_update'),
('tours', 1, 'delete'),
('tours', 1, 'edit_sale'),
('tours', 1, 'search'),
('transportations', 1, 'add_update'),
('transportations', 1, 'delete'),
('transportations', 1, 'search'),
('customers', 214, 'add_update'),
('customers', 214, 'delete'),
('customers', 214, 'search'),
('suppliers', 214, 'add_update'),
('suppliers', 214, 'delete'),
('suppliers', 214, 'search'),
('customers', 217, 'add_update'),
('customers', 217, 'delete'),
('customers', 217, 'search'),
('suppliers', 217, 'add_update'),
('suppliers', 217, 'delete'),
('suppliers', 217, 'search'),
('sales', 218, 'edit_sale'),
('sales', 218, 'edit_sale_price'),
('sales', 218, 'give_discount'),
('tickets', 218, 'add_update'),
('tickets', 218, 'delete'),
('tickets', 218, 'edit_sale'),
('tickets', 218, 'edit_sale_price'),
('tickets', 218, 'give_discount'),
('tickets', 218, 'search'),
('customers', 219, 'add_update'),
('customers', 219, 'delete'),
('customers', 219, 'search'),
('suppliers', 220, 'add_update'),
('suppliers', 220, 'delete'),
('suppliers', 220, 'search'),
('customers', 221, 'add_update'),
('customers', 221, 'delete'),
('customers', 221, 'search'),
('suppliers', 221, 'add_update'),
('suppliers', 221, 'delete'),
('suppliers', 221, 'search'),
('customers', 222, 'add_update'),
('customers', 222, 'delete'),
('customers', 222, 'search'),
('suppliers', 222, 'add_update'),
('suppliers', 222, 'delete'),
('suppliers', 222, 'search'),
('customers', 223, 'add_update'),
('customers', 223, 'delete'),
('customers', 223, 'search'),
('employees', 223, 'add_update'),
('employees', 223, 'delete'),
('employees', 223, 'search'),
('suppliers', 223, 'add_update'),
('suppliers', 223, 'delete'),
('suppliers', 223, 'search'),
('customers', 224, 'add_update'),
('customers', 224, 'delete'),
('customers', 224, 'search'),
('customers', 225, 'add_update'),
('customers', 225, 'delete'),
('customers', 225, 'search'),
('suppliers', 225, 'add_update'),
('suppliers', 225, 'delete'),
('suppliers', 225, 'search'),
('customers', 233, 'add_update'),
('customers', 233, 'delete'),
('customers', 233, 'search'),
('employees', 236, 'add_update'),
('employees', 236, 'delete'),
('employees', 236, 'search'),
('sales', 236, 'edit_sale'),
('sales', 236, 'edit_sale_price'),
('sales', 236, 'give_discount'),
('tickets', 236, 'add_update'),
('tickets', 236, 'delete'),
('tickets', 236, 'edit_sale'),
('tickets', 236, 'edit_sale_price'),
('tickets', 236, 'give_discount'),
('tickets', 236, 'search'),
('sales', 240, 'edit_sale'),
('sales', 240, 'edit_sale_price'),
('sales', 240, 'give_discount'),
('tickets', 240, 'add_update'),
('tickets', 240, 'delete'),
('tickets', 240, 'edit_sale'),
('tickets', 240, 'edit_sale_price'),
('tickets', 240, 'give_discount'),
('tickets', 240, 'search');

-- --------------------------------------------------------

--
-- Table structure for table `cgate_permissions_office`
--

CREATE TABLE IF NOT EXISTS `cgate_permissions_office` (
  `office_id` int(10) NOT NULL,
  `person_id` int(10) NOT NULL,
  PRIMARY KEY (`office_id`,`person_id`),
  KEY `person_id` (`person_id`),
  KEY `fk_cgate_permissions_cgate_offices1_idx` (`office_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `cgate_permissions_office`
--

INSERT INTO `cgate_permissions_office` (`office_id`, `person_id`) VALUES
(1, 1),
(2, 1),
(3, 1),
(4, 1),
(1, 214),
(2, 214),
(1, 217),
(2, 217),
(3, 217),
(1, 218),
(2, 218),
(3, 218),
(1, 219),
(2, 219),
(1, 220),
(2, 220),
(1, 221),
(3, 221),
(1, 222),
(2, 222),
(1, 223),
(2, 223),
(1, 224),
(3, 224),
(1, 225),
(2, 225),
(1, 233),
(2, 233),
(1, 236),
(1, 240);

-- --------------------------------------------------------

--
-- Table structure for table `cgate_positions`
--

CREATE TABLE IF NOT EXISTS `cgate_positions` (
  `position_id` int(11) NOT NULL AUTO_INCREMENT,
  `position_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'For stor record as kit/package',
  `module_id` varchar(225) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`position_id`),
  UNIQUE KEY `item_kit_number` (`position_name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

--
-- Dumping data for table `cgate_positions`
--

INSERT INTO `cgate_positions` (`position_id`, `position_name`, `module_id`) VALUES
(1, 'Owner', 'config'),
(2, 'Sales', 'customers');

-- --------------------------------------------------------

--
-- Table structure for table `cgate_receivings`
--

CREATE TABLE IF NOT EXISTS `cgate_receivings` (
  `receiving_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `supplierID` int(10) DEFAULT NULL,
  `employeeID` int(10) NOT NULL DEFAULT '0',
  `comment` text COLLATE utf8_unicode_ci NOT NULL,
  `receiving_id` int(10) NOT NULL AUTO_INCREMENT,
  `payment_type` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `deleted` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`receiving_id`),
  KEY `supplier_id` (`supplierID`),
  KEY `employee_id` (`employeeID`),
  KEY `deleted` (`deleted`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cgate_receivings_items`
--

CREATE TABLE IF NOT EXISTS `cgate_receivings_items` (
  `receiving_id` int(10) NOT NULL DEFAULT '0',
  `item_id` int(10) NOT NULL DEFAULT '0',
  `description` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `serialnumber` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `line` int(3) NOT NULL,
  `quantity_purchased` int(10) NOT NULL DEFAULT '0',
  `item_cost_price` decimal(15,2) NOT NULL,
  `item_unit_price` decimal(15,2) NOT NULL,
  `discount_percent` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`receiving_id`,`item_id`,`line`),
  KEY `fk_phppos_receivings_items_items_bikes1_idx` (`item_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cgate_register_logs`
--

CREATE TABLE IF NOT EXISTS `cgate_register_logs` (
  `register_log_id` int(10) NOT NULL AUTO_INCREMENT,
  `employeeID` int(10) NOT NULL,
  `shift_start` timestamp NULL DEFAULT NULL,
  `shift_end` timestamp NULL DEFAULT NULL,
  `open_amount` double(15,2) NOT NULL,
  `close_amount` double(15,2) NOT NULL,
  `cash_sales_amount` double(15,2) NOT NULL,
  PRIMARY KEY (`register_log_id`),
  KEY `phppos_register_log_ibfk_1` (`employeeID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cgate_sales_items_taxes`
--

CREATE TABLE IF NOT EXISTS `cgate_sales_items_taxes` (
  `sale_ticket_id` int(10) NOT NULL,
  `ticket_id` int(10) NOT NULL,
  `line` int(3) NOT NULL DEFAULT '0',
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `percent` decimal(15,3) NOT NULL,
  `cumulative` int(1) NOT NULL DEFAULT '0',
  `sale_type` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `category` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`sale_ticket_id`,`ticket_id`,`line`,`name`,`percent`,`cumulative`,`category`),
  KEY `ticket_id` (`ticket_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cgate_sales_item_kits_taxes`
--

CREATE TABLE IF NOT EXISTS `cgate_sales_item_kits_taxes` (
  `saleID` int(10) NOT NULL,
  `item_kitID` int(10) NOT NULL,
  `line` int(3) NOT NULL DEFAULT '0',
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `percent` decimal(15,3) NOT NULL,
  `cumulative` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`saleID`,`item_kitID`,`line`,`name`,`percent`),
  KEY `item_id` (`item_kitID`),
  KEY `fk_cgate_sales_item_kits_taxes_cgate_orders_item_kits1_idx` (`saleID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cgate_sales_payments`
--

CREATE TABLE IF NOT EXISTS `cgate_sales_payments` (
  `payment_id` int(10) NOT NULL AUTO_INCREMENT,
  `sale_id` int(10) NOT NULL,
  `payment_type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `payment_amount` decimal(15,2) NOT NULL,
  `payment_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `sale_type` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`payment_id`),
  KEY `fk_cgate_sales_payments_cgate_orders_tours1` (`sale_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=694 ;

--
-- Dumping data for table `cgate_sales_payments`
--

INSERT INTO `cgate_sales_payments` (`payment_id`, `sale_id`, `payment_type`, `payment_amount`, `payment_date`, `sale_type`) VALUES
(175, 123, 'Cash', '15.00', '2014-01-08 07:14:20', NULL),
(176, 124, 'Cash', '15.00', '2014-01-08 07:43:57', NULL),
(177, 125, 'Cash', '10.00', '2014-01-08 07:47:48', NULL),
(178, 126, 'Cash', '10.00', '2014-01-08 07:53:21', NULL),
(179, 127, 'Cash', '10.00', '2014-01-08 07:58:07', NULL),
(180, 128, 'Cash', '15.00', '2014-01-08 08:04:03', NULL),
(181, 129, 'Cash', '10.00', '2014-01-08 08:06:18', NULL),
(182, 130, 'Cash', '25.00', '2014-01-08 08:15:50', NULL),
(186, 134, 'Cash', '20.00', '2014-01-09 01:18:47', NULL),
(187, 135, 'Cash', '15.00', '2014-01-09 02:57:00', NULL),
(188, 135, 'Cash', '25.00', '2014-01-09 02:57:32', NULL),
(278, 187, 'Cash', '25.00', '2014-01-18 02:32:12', NULL),
(279, 188, 'Cash', '25.00', '2014-01-18 03:08:59', NULL),
(286, 195, 'Cash', '25.00', '2014-01-18 03:08:59', NULL),
(287, 196, 'Cash', '15.00', '2014-01-18 04:06:44', NULL),
(288, 196, 'Cash', '15.00', '2014-01-18 04:06:44', NULL),
(289, 196, 'Cash', '15.00', '2014-01-18 04:06:44', NULL),
(290, 196, 'Cash', '15.00', '2014-01-18 04:06:44', NULL),
(291, 196, 'Cash', '15.00', '2014-01-18 04:06:44', NULL),
(292, 196, 'Cash', '15.00', '2014-01-18 04:06:44', NULL),
(293, 196, 'Cash', '15.00', '2014-01-18 04:06:44', NULL),
(294, 196, 'Cash', '15.00', '2014-01-18 04:06:44', NULL),
(295, 196, 'Cash', '15.00', '2014-01-18 04:06:44', NULL),
(296, 196, 'Cash', '15.00', '2014-01-18 04:06:44', NULL),
(297, 196, 'Cash', '15.00', '2014-01-18 04:06:44', NULL),
(298, 196, 'Cash', '15.00', '2014-01-18 04:06:44', NULL),
(299, 196, 'Cash', '15.00', '2014-01-18 04:06:44', NULL),
(300, 196, 'Cash', '15.00', '2014-01-18 04:06:44', NULL),
(301, 196, 'Cash', '15.00', '2014-01-18 04:06:44', NULL),
(302, 196, 'Cash', '15.00', '2014-01-18 04:06:44', NULL),
(303, 196, 'Cash', '15.00', '2014-01-18 04:06:44', NULL),
(304, 196, 'Cash', '15.00', '2014-01-18 04:06:44', NULL),
(305, 196, 'Cash', '15.00', '2014-01-18 04:06:44', NULL),
(306, 196, 'Cash', '15.00', '2014-01-18 04:06:44', NULL),
(307, 196, 'Cash', '15.00', '2014-01-18 04:06:44', NULL),
(308, 196, 'Cash', '15.00', '2014-01-18 04:06:44', NULL),
(309, 196, 'Cash', '15.00', '2014-01-18 04:06:44', NULL),
(310, 196, 'Cash', '15.00', '2014-01-18 04:06:44', NULL),
(311, 196, 'Cash', '15.00', '2014-01-18 04:06:44', NULL),
(312, 196, 'Cash', '15.00', '2014-01-18 04:06:44', NULL),
(313, 196, 'Cash', '15.00', '2014-01-18 04:06:44', NULL),
(314, 196, 'Cash', '15.00', '2014-01-18 04:06:44', NULL),
(315, 196, 'Cash', '15.00', '2014-01-18 04:06:44', NULL),
(316, 196, 'Cash', '15.00', '2014-01-18 04:06:44', NULL),
(317, 196, 'Cash', '15.00', '2014-01-18 04:06:44', NULL),
(318, 196, 'Cash', '15.00', '2014-01-18 04:06:44', NULL),
(319, 196, 'Cash', '15.00', '2014-01-18 04:06:44', NULL),
(320, 196, 'Cash', '15.00', '2014-01-18 04:06:44', NULL),
(321, 196, 'Cash', '15.00', '2014-01-18 04:06:44', NULL),
(322, 196, 'Cash', '15.00', '2014-01-18 04:06:44', NULL),
(323, 196, 'Cash', '15.00', '2014-01-18 04:06:44', NULL),
(324, 196, 'Cash', '15.00', '2014-01-18 04:06:44', NULL),
(325, 196, 'Cash', '15.00', '2014-01-18 04:06:44', NULL),
(326, 196, 'Cash', '15.00', '2014-01-18 04:06:44', NULL),
(327, 196, 'Cash', '15.00', '2014-01-18 04:06:44', NULL),
(328, 196, 'Cash', '15.00', '2014-01-18 04:06:44', NULL),
(329, 196, 'Cash', '15.00', '2014-01-18 04:06:44', NULL),
(330, 196, 'Cash', '15.00', '2014-01-18 04:06:44', NULL),
(331, 196, 'Cash', '15.00', '2014-01-18 04:06:44', NULL),
(332, 196, 'Cash', '15.00', '2014-01-18 04:06:44', NULL),
(333, 196, 'Cash', '15.00', '2014-01-18 04:06:44', NULL),
(334, 196, 'Cash', '15.00', '2014-01-18 04:06:44', NULL),
(335, 196, 'Cash', '15.00', '2014-01-18 04:06:44', NULL),
(336, 196, 'Cash', '15.00', '2014-01-18 04:06:44', NULL),
(337, 196, 'Cash', '15.00', '2014-01-18 04:06:44', NULL),
(338, 196, 'Cash', '15.00', '2014-01-18 04:06:44', NULL),
(339, 196, 'Cash', '15.00', '2014-01-18 04:06:44', NULL),
(340, 196, 'Cash', '15.00', '2014-01-18 04:06:44', NULL),
(341, 196, 'Cash', '15.00', '2014-01-18 04:06:44', NULL),
(342, 196, 'Cash', '15.00', '2014-01-18 04:06:44', NULL),
(343, 196, 'Cash', '15.00', '2014-01-18 04:06:44', NULL),
(344, 196, 'Cash', '15.00', '2014-01-18 04:06:44', NULL),
(345, 196, 'Cash', '15.00', '2014-01-18 04:06:44', NULL),
(346, 196, 'Cash', '15.00', '2014-01-18 04:06:44', NULL),
(347, 196, 'Cash', '15.00', '2014-01-18 04:06:44', NULL),
(348, 196, 'Cash', '15.00', '2014-01-18 04:06:44', NULL),
(349, 196, 'Cash', '15.00', '2014-01-18 04:06:44', NULL),
(350, 196, 'Cash', '15.00', '2014-01-18 04:06:44', NULL),
(351, 197, 'Cash', '15.00', '2014-01-18 04:54:07', NULL),
(352, 197, 'Cash', '15.00', '2014-01-18 04:54:07', NULL),
(353, 197, 'Cash', '15.00', '2014-01-18 04:54:07', NULL),
(354, 197, 'Cash', '15.00', '2014-01-18 04:54:07', NULL),
(355, 198, 'Cash', '10.00', '2014-01-18 05:02:44', NULL),
(356, 198, 'Cash', '10.00', '2014-01-18 05:02:44', NULL),
(357, 198, 'Cash', '10.00', '2014-01-18 05:02:44', NULL),
(358, 198, 'Cash', '10.00', '2014-01-18 05:02:44', NULL),
(359, 198, 'Cash', '10.00', '2014-01-18 05:02:44', NULL),
(360, 198, 'Cash', '10.00', '2014-01-18 05:02:44', NULL),
(361, 198, 'Cash', '10.00', '2014-01-18 05:02:44', NULL),
(362, 198, 'Cash', '10.00', '2014-01-18 05:02:44', NULL),
(363, 199, 'Cash', '10.00', '2014-01-18 05:08:43', NULL),
(364, 199, 'Cash', '10.00', '2014-01-18 05:08:43', NULL),
(365, 199, 'Cash', '10.00', '2014-01-18 05:08:43', NULL),
(366, 200, 'Cash', '10.00', '2014-01-18 05:13:40', NULL),
(367, 201, 'Cash', '50.00', '2014-01-19 15:41:41', NULL),
(368, 201, 'Cash', '50.00', '2014-01-19 15:41:41', NULL),
(369, 202, 'Cash', '22.50', '2014-01-20 01:29:13', NULL),
(370, 202, 'Cash', '22.50', '2014-01-20 01:29:13', NULL),
(371, 202, 'Cash', '22.50', '2014-01-20 01:29:13', NULL),
(372, 202, 'Cash', '22.50', '2014-01-20 01:29:13', NULL),
(373, 202, 'Cash', '22.50', '2014-01-20 01:29:13', NULL),
(374, 203, 'Cash', '15.00', '2014-01-20 02:16:08', NULL),
(375, 203, 'Cash', '15.00', '2014-01-20 02:16:08', NULL),
(376, 203, 'Cash', '15.00', '2014-01-20 02:16:08', NULL),
(377, 203, 'Cash', '15.00', '2014-01-20 02:16:08', NULL),
(378, 204, 'Cash', '25.00', '2014-01-20 02:21:06', NULL),
(386, 204, 'Cash', '25.00', '2014-01-20 02:21:06', NULL),
(387, 204, 'Cash', '25.00', '2014-01-20 02:21:06', NULL),
(388, 204, 'Cash', '25.00', '2014-01-20 02:21:06', NULL),
(389, 205, 'Cash', '15.00', '2014-01-20 03:52:21', NULL),
(398, 206, 'Cash', '15.00', '2014-01-20 04:21:59', NULL),
(400, 207, 'Cash', '25.00', '2014-01-20 04:34:51', NULL),
(401, 207, 'Cash', '25.00', '2014-01-20 04:34:51', NULL),
(402, 208, 'Cash', '25.00', '2014-01-20 04:50:25', NULL),
(403, 208, 'Cash', '25.00', '2014-01-20 04:53:02', NULL),
(404, 218, 'Cash', '18.00', '2014-01-23 03:32:32', NULL),
(405, 219, 'Cash', '11.64', '2014-01-23 03:36:42', NULL),
(406, 220, 'Cash', '31.00', '2014-01-23 09:56:48', NULL),
(407, 221, 'Cash', '9.00', '2014-01-23 15:31:20', NULL),
(408, 222, 'Cash', '8.10', '2014-01-24 01:16:24', NULL),
(409, 223, 'Cash', '9.00', '2014-01-25 06:50:25', NULL),
(410, 224, 'Cash', '9.50', '2014-01-25 15:39:50', NULL),
(411, 225, 'Cash', '9.50', '2014-01-25 16:00:41', NULL),
(412, 226, 'Cash', '9.00', '2014-01-25 16:13:10', NULL),
(413, 226, 'Cash', '-0.45', '2014-01-25 16:13:32', NULL),
(414, 227, 'Cash', '16.30', '2014-01-28 07:26:38', NULL),
(422, 235, 'Cash', '11.00', '2014-01-31 03:06:52', NULL),
(423, 236, 'Cash', '26.00', '2014-01-31 03:22:34', NULL),
(431, 244, 'Cash', '45.00', '2014-01-31 03:53:26', 'tours'),
(432, 245, 'Cash', '12.00', '2014-01-31 09:09:09', 'tours'),
(433, 246, 'Cash', '12.00', '2014-01-31 09:20:59', 'tours'),
(434, 247, 'Cash', '45.00', '2014-02-01 01:20:53', 'tours'),
(435, 248, 'Cash', '45.00', '2014-02-01 01:39:37', 'tours'),
(436, 249, 'Cash', '45.00', '2014-02-01 01:39:37', 'tours'),
(437, 250, 'Cash', '12.00', '2014-02-01 02:29:23', 'tours'),
(438, 251, 'Cash', '25.00', '2014-02-01 03:03:30', 'tours'),
(439, 252, 'Cash', '15.00', '2014-02-01 03:05:08', 'tours'),
(445, 254, 'Cash', '26.00', '2014-02-03 01:59:09', 'tours'),
(446, 255, 'Cash', '26.00', '2014-02-03 03:03:16', 'tours'),
(447, 256, 'Cash', '40.00', '2014-02-03 03:16:01', 'tours'),
(465, 257, 'Cash', '30.00', '2014-02-03 03:17:46', 'tours'),
(467, 259, 'Cash', '5.00', '2014-02-04 06:33:56', NULL),
(508, 301, 'Cash', '8.00', '2014-02-05 03:57:35', 'tickets'),
(514, 311, 'Cash', '7.00', '2014-02-05 06:50:13', 'tickets'),
(516, 313, 'Cash', '11.00', '2014-02-05 07:06:00', 'tickets'),
(517, 314, 'Cash', '12.00', '2014-02-05 07:07:33', 'tours'),
(518, 315, 'Cash', '12.00', '2014-02-05 07:48:47', 'tours'),
(520, 316, 'Cash', '30.00', '2014-02-05 07:54:58', 'tours'),
(521, 253, 'Cash', '15.00', '2014-02-01 03:10:46', 'tours'),
(522, 313, 'Cash', '11.00', '2014-02-05 07:06:00', 'tickets'),
(568, 317, 'Cash', '11.00', '2014-02-06 03:47:58', 'tickets'),
(569, 317, 'Cash', '1.00', '2014-02-06 04:07:08', 'tickets'),
(570, 317, 'Cash', '2.00', '2014-02-06 04:39:35', 'tickets'),
(572, 318, 'Cash', '14.00', '2014-02-06 08:10:26', 'tickets'),
(575, 258, 'Cash', '9.00', '2014-02-04 03:14:41', 'tickets'),
(576, 258, 'Cash', '9.00', '2014-02-04 03:14:41', 'tickets'),
(578, 319, 'Cash', '16.00', '2014-02-07 04:17:35', 'tickets'),
(581, 320, 'Cash', '15.00', '2014-02-07 16:40:42', 'tickets'),
(584, 321, 'Cash', '11.00', '2014-02-07 17:29:27', 'tickets'),
(591, 328, 'Cash', '15.00', '2014-02-07 17:49:40', 'tickets'),
(592, 329, 'Cash', '15.00', '2014-02-07 17:49:40', 'tickets'),
(593, 329, 'Cash', '0.00', '2014-02-07 17:56:42', 'tickets'),
(595, 330, 'Cash', '8.00', '2014-02-07 17:58:53', 'tickets'),
(596, 330, 'Cash', '9.00', '2014-02-07 17:59:41', 'tickets'),
(598, 331, 'Cash', '26.00', '2014-02-07 18:03:48', 'tours'),
(605, 338, 'Cash', '43.00', '2014-02-08 09:59:26', 'massages'),
(606, 339, 'Cash', '43.00', '2014-02-08 17:00:04', 'massages'),
(607, 340, 'Cash', '15.00', '2014-02-09 16:58:39', 'tickets'),
(609, 341, 'Cash', '26.00', '2014-02-10 03:37:22', 'tickets'),
(611, 342, 'Cash', '136.00', '2014-02-12 02:35:10', 'tickets'),
(612, 343, 'Cash', '61.00', '2014-02-12 03:12:39', 'tours'),
(635, 345, 'Check', '10.00', '2014-02-14 03:59:20', 'tickets'),
(636, 345, 'Cash', '126.00', '2014-02-14 04:07:32', 'tickets'),
(643, 344, 'Check', '136.00', '2014-02-12 17:52:33', 'tickets'),
(644, 344, 'Credit Card', '0.00', '2014-02-13 02:34:43', 'tickets'),
(645, 346, 'Cash', '11.00', '2014-02-24 07:27:37', 'tickets'),
(646, 347, 'Cash', '11.00', '2014-02-24 07:27:37', 'tickets'),
(647, 347, 'Cash', '126.00', '2014-02-24 07:28:37', 'tickets'),
(648, 348, 'Cash', '11.00', '2014-02-24 07:27:37', 'tickets'),
(649, 348, 'Cash', '126.00', '2014-02-24 07:28:37', 'tickets'),
(650, 349, 'Cash', '11.00', '2014-02-25 01:09:03', 'tickets'),
(651, 350, 'Cash', '11.00', '2014-02-25 01:17:25', 'tickets'),
(652, 351, 'Cash', '12.00', '2014-02-25 01:31:47', 'tickets'),
(661, 353, 'Cash', '126.00', '2014-02-26 02:27:06', 'tickets'),
(662, 352, 'Cash', '15.00', '2014-02-26 01:22:17', 'tickets'),
(663, 352, 'Cash', '11.00', '2014-02-26 07:47:30', 'tickets'),
(664, 354, 'Cash', '12.00', '2014-03-06 19:19:34', 'massages'),
(666, 356, 'Cash', '136.00', '2014-03-07 10:14:38', 'tickets'),
(668, 357, 'Cash', '136.00', '2014-03-07 10:14:38', 'tickets'),
(672, 355, 'Cash', '11.00', '2014-03-07 14:07:51', 'tickets'),
(674, 360, 'Cash', '10.00', '2014-03-11 08:47:23', 'tickets'),
(675, 361, 'Cash', '25.00', '2014-03-13 03:35:35', 'tickets'),
(676, 359, 'Cash', '126.00', '2014-03-13 03:39:34', 'tickets'),
(677, 362, 'Cash', '35.00', '2014-03-14 18:36:12', 'tours'),
(678, 364, 'Cash', '11.00', '2014-03-15 16:55:10', 'tickets'),
(679, 365, 'Cash', '11.00', '2014-03-15 16:56:05', 'tickets'),
(680, 366, 'Cash', '12.00', '2014-03-15 16:59:16', 'tickets'),
(681, 367, 'Cash', '11.00', '2014-03-15 17:50:01', 'tickets'),
(682, 367, 'Cash', '11.00', '2014-03-15 17:50:06', 'tickets'),
(683, 368, 'Cash', '11.00', '2014-03-15 18:14:24', 'tickets'),
(684, 368, 'Cash', '11.00', '2014-03-15 18:14:27', 'tickets'),
(688, 358, 'Cash', '136.00', '2014-03-07 10:14:38', 'tickets'),
(689, 369, 'Cash', '126.00', '2014-03-15 21:03:35', 'tickets'),
(690, 370, 'Cash', '126.00', '2014-03-15 21:06:13', 'tickets'),
(691, 371, 'Cash', '30.00', '2014-03-16 07:04:01', 'tickets'),
(692, 372, 'Cash', '15.00', '2014-03-17 01:19:55', 'massages'),
(693, 373, 'Cash', '15.00', '2014-03-17 01:19:55', 'massages');

-- --------------------------------------------------------

--
-- Table structure for table `cgate_sessions`
--

CREATE TABLE IF NOT EXISTS `cgate_sessions` (
  `session_id` varchar(40) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `ip_address` varchar(45) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `user_agent` varchar(120) COLLATE utf8_unicode_ci NOT NULL,
  `last_activity` int(10) unsigned NOT NULL DEFAULT '0',
  `user_data` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`session_id`),
  KEY `last_activity_idx` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `cgate_sessions`
--

INSERT INTO `cgate_sessions` (`session_id`, `ip_address`, `user_agent`, `last_activity`, `user_data`) VALUES
('34d583b27d098ad41e46ba2829fdba40', '::1', 'Mozilla/5.0 (Windows NT 6.2; WOW64; rv:28.0) Gecko/20100101 Firefox/28.0', 1395018919, 'a:5:{s:9:"user_data";s:0:"";s:9:"person_id";s:1:"1";s:13:"office_number";s:7:"world_1";s:8:"cur_page";s:7:"world_1";s:10:"pagination";s:2:"20";}');

-- --------------------------------------------------------

--
-- Table structure for table `cgate_suppliers`
--

CREATE TABLE IF NOT EXISTS `cgate_suppliers` (
  `supplier_id` int(10) NOT NULL,
  `company_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `account_number` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `deleted` int(1) NOT NULL DEFAULT '0',
  `supplier_typeID` int(10) DEFAULT NULL,
  PRIMARY KEY (`supplier_id`),
  UNIQUE KEY `account_number` (`account_number`),
  KEY `person_id` (`supplier_id`),
  KEY `deleted` (`deleted`),
  KEY `fk_suppliers_type_suppliers` (`supplier_typeID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `cgate_suppliers`
--

INSERT INTO `cgate_suppliers` (`supplier_id`, `company_name`, `account_number`, `deleted`, `supplier_typeID`) VALUES
(6, 'ABCD Bicycle', '00001', 1, 3),
(7, 'Bsokry Bus', '00002', 0, 1),
(25, 'Codingate', '01000', 0, NULL),
(29, 'C&C Organization', '06000', 1, NULL),
(30, 'Hinghorng_a', '07000', 0, NULL),
(31, 'Thida company', '09000', 0, NULL),
(32, 'CCC Company', '00003', 0, NULL),
(33, 'sreychenComapny', '00004', 0, NULL),
(35, 'Lyhong', '00005', 0, NULL),
(36, 'CCD', '00006', 0, NULL),
(37, 'adidas', '00909', 0, NULL),
(38, 'lala Company', '00008', 0, NULL),
(39, 'danda', '00009', 0, NULL),
(40, 'hongda', '00010', 0, NULL),
(41, 'jjj', '987654', 0, NULL),
(42, 'C&C', '98765', 1, NULL),
(43, 'cheko company', '00100', 0, NULL),
(45, 'Natra', '00101', 0, NULL),
(49, 'kaka', '000102', 0, NULL),
(50, 'lolo', '000104', 1, NULL),
(51, 'hongkong', '000105', 0, NULL),
(82, 'Codingating', '010010', 0, NULL),
(86, 'sreynak company', '002121', 0, NULL),
(87, 'ddd', '0100001', 1, NULL),
(88, 'x''mak', '0009876', 0, NULL),
(89, 'z hhuman', '009878', 0, NULL),
(90, 'flower', '0087767', 0, NULL),
(102, 'yyy', '009766', 0, NULL),
(243, 'aaa', '00090956', 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `cgate_suppliers_offices`
--

CREATE TABLE IF NOT EXISTS `cgate_suppliers_offices` (
  `supplier_id` int(10) NOT NULL,
  `office_id` int(10) NOT NULL,
  PRIMARY KEY (`supplier_id`,`office_id`),
  KEY `fk_suppliers_offices_offices` (`office_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `cgate_suppliers_offices`
--

INSERT INTO `cgate_suppliers_offices` (`supplier_id`, `office_id`) VALUES
(6, 1),
(7, 1),
(37, 1),
(42, 1);

-- --------------------------------------------------------

--
-- Table structure for table `cgate_suppliers_types`
--

CREATE TABLE IF NOT EXISTS `cgate_suppliers_types` (
  `supplier_type_id` int(10) NOT NULL AUTO_INCREMENT,
  `supplier_type_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `deleted` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`supplier_type_id`),
  KEY `deleted` (`deleted`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;

--
-- Dumping data for table `cgate_suppliers_types`
--

INSERT INTO `cgate_suppliers_types` (`supplier_type_id`, `supplier_type_name`, `deleted`) VALUES
(1, 'bus', 0),
(2, 'ticket', 0),
(3, 'bike', 0);

-- --------------------------------------------------------

--
-- Table structure for table `cgate_tickets`
--

CREATE TABLE IF NOT EXISTS `cgate_tickets` (
  `ticket_id` int(10) NOT NULL AUTO_INCREMENT,
  `ticket_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `descriptions` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `destinationID` int(10) NOT NULL,
  `supplierID` int(10) NOT NULL,
  `ticket_typeID` int(10) NOT NULL,
  `actual_price` decimal(15,2) DEFAULT NULL,
  `sale_price` decimal(15,2) DEFAULT NULL,
  `deleted` tinyint(2) DEFAULT '0',
  `quantity` decimal(15,2) DEFAULT '1.00',
  PRIMARY KEY (`ticket_id`,`ticket_name`),
  KEY `fk_cgate_tickets_cgate_destinations1_idx` (`destinationID`),
  KEY `fk_cgate_tickets_cgate_tickets_types1_idx` (`ticket_typeID`),
  KEY `fk_cgate_tickets_cgate_suppliers` (`supplierID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=55 ;

--
-- Dumping data for table `cgate_tickets`
--

INSERT INTO `cgate_tickets` (`ticket_id`, `ticket_name`, `descriptions`, `destinationID`, `supplierID`, `ticket_typeID`, `actual_price`, `sale_price`, `deleted`, `quantity`) VALUES
(1, 'testing28', '', 1, 0, 1, '10.00', '15.00', 0, '-1.00'),
(2, 'testing27', '', 1, 0, 1, '10.00', '15.00', 0, '0.00'),
(3, 'testing26', '', 1, 0, 1, '10.00', '15.00', 0, '0.00'),
(4, 'testing25', '', 1, 0, 1, '15.00', '20.00', 0, '0.00'),
(16, 'testing24', '', 1, 0, 1, '10.00', '15.00', 0, '0.00'),
(17, 'testing23', '', 1, 0, 1, '10.00', '12.00', 0, '0.00'),
(18, 'testing22', '', 1, 0, 1, '10.00', '15.00', 0, '0.00'),
(19, 'testing21', '', 1, 0, 1, '10.00', '12.00', 0, '0.00'),
(20, 'testing20', '', 1, 0, 1, '10.00', '11.00', 0, '0.00'),
(21, 'testing19 edit', 'Just testing', 1, 0, 1, '10.00', '11.00', 0, '0.00'),
(22, 'testing18', '', 1, 0, 1, '10.00', '13.00', 0, '0.00'),
(23, 'testing17', '', 2, 0, 2, '10.00', '12.00', 0, '0.00'),
(30, 'testing16', '', 2, 0, 1, '10.00', '10.00', 0, '0.00'),
(31, 'testing15', 'Hello world', 2, 37, 1, '10.00', '10.00', 0, '0.00'),
(32, 'testing14', '', 2, 0, 1, '10.00', '10.00', 1, '0.00'),
(33, 'testing13', '', 2, 0, 1, '10.00', '10.00', 1, '0.00'),
(34, 'testing12', '', 2, 0, 1, '9.00', '8.00', 0, '0.00'),
(35, 'testing11', '', 2, 0, 1, '10.00', '9.00', 0, '0.00'),
(36, 'testing10', '', 2, 0, 2, '10.00', '10.00', 0, '0.00'),
(37, 'testing9', '', 2, 0, 1, '10.00', '7.00', 0, '0.00'),
(38, 'testing8', '', 2, 0, 1, '10.00', '8.00', 0, '0.00'),
(39, 'testing7', '', 2, 0, 1, '10.00', '7.00', 0, '0.00'),
(40, 'testing6', '', 3, 0, 1, '10.00', '5.00', 0, '0.00'),
(41, 'testing5', '', 2, 0, 1, '10.00', '9.00', 0, '0.00'),
(42, 'testing4', '', 3, 0, 1, '10.00', '8.00', 0, '0.00'),
(43, 'testing3', '', 2, 0, 1, '12.00', '11.00', 0, '1.00'),
(44, 'testing2', '', 2, 0, 2, '10.00', '8.00', 0, '1.00'),
(45, 'testing1', '', 3, 0, 1, '12.00', '10.00', 0, '1.00'),
(46, 'AA Ticket', 'Hello worl', 9, 40, 1, '12.00', '15.00', 0, '1.00'),
(47, 'Takeo ticket 10:00', '', 1, 29, 1, '10.00', '10.00', 0, '1.00'),
(48, 'Takeo ticket how 10:00 La', '', 1, 7, 1, '14.00', '11.00', 0, '1.00'),
(49, 'Takeo ticket is how 10:00', '', 1, 36, 1, '14.00', '12.00', 0, '1.00'),
(50, 'Phnom Penh - Kampot leave 09:00', '', 4, 37, 3, '14.00', '11.00', 0, '1.00'),
(51, 'Siem Reap - Kampong Chhnang 06:00 AM', 'Go for a walk', 5, 36, 3, '135.00', '126.00', 0, '1.00'),
(52, 'Phnom Penh - Angkor Wat', 'This is for going to Siem Reap to visit Angkor Wat', 6, 102, 1, '7.00', '11.00', 0, '1.00'),
(54, 'pp - prey veng province ', 'this is the new ticket', 9, 87, 1, '12.00', '14.00', 0, '1.00');

-- --------------------------------------------------------

--
-- Table structure for table `cgate_tickets_types`
--

CREATE TABLE IF NOT EXISTS `cgate_tickets_types` (
  `ticket_type_id` int(10) NOT NULL AUTO_INCREMENT,
  `ticket_type_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `deleted` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ticket_type_id`),
  KEY `deleted` (`deleted`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=5 ;

--
-- Dumping data for table `cgate_tickets_types`
--

INSERT INTO `cgate_tickets_types` (`ticket_type_id`, `ticket_type_name`, `deleted`) VALUES
(1, 'bus', 0),
(2, 'ven', 0),
(3, 'fly', 0),
(4, 'ship', 0);

-- --------------------------------------------------------

--
-- Table structure for table `cgate_tours`
--

CREATE TABLE IF NOT EXISTS `cgate_tours` (
  `tour_id` int(10) NOT NULL AUTO_INCREMENT,
  `tour_name` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `departure_date` date NOT NULL,
  `departure_time` time NOT NULL,
  `description` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `by` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `actual_price` decimal(10,0) NOT NULL,
  `sale_price` decimal(10,0) NOT NULL,
  `destinationID` int(11) DEFAULT NULL,
  `supplier_id` int(11) DEFAULT NULL,
  `deleted` tinyint(2) NOT NULL DEFAULT '0',
  PRIMARY KEY (`tour_id`,`tour_name`),
  KEY `fk_tour_destination_id` (`destinationID`),
  KEY `fk_tour_suppliers` (`supplier_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=21 ;

--
-- Dumping data for table `cgate_tours`
--

INSERT INTO `cgate_tours` (`tour_id`, `tour_name`, `departure_date`, `departure_time`, `description`, `by`, `actual_price`, `sale_price`, `destinationID`, `supplier_id`, `deleted`) VALUES
(1, 'Tour for going to Kampot only tour item', '0000-00-00', '00:00:00', '', '', '50', '45', 2, 6, 0),
(3, 'Tour testing', '0000-00-00', '00:00:00', '', '', '14', '11', 1, 29, 1),
(4, 'Tour hello', '0000-00-00', '00:00:00', 'This is the testing of tour record', '', '14', '15', 1, 7, 1),
(5, 'Tour hello world', '0000-00-00', '00:00:00', 'This is the testing of tour record', '', '14', '15', 1, 7, 0),
(6, 'Hi tourism', '1977-02-23', '00:00:00', 'Hello everybody', '', '20', '25', 1, 25, 0),
(7, 'Good morning tour', '2014-03-19', '00:00:00', 'This is the way in business', '', '35', '40', 3, 36, 0),
(8, 'Hello my tour', '2014-03-28', '10:00:00', 'Hello world he', 'ven', '30', '35', 3, 29, 1),
(9, 'Galla Dinner touring', '2014-05-15', '00:00:00', 'Hello Hinghorng company, kill', 'bus', '20', '26', 1, 30, 0),
(14, 'testing tour takeo', '2014-02-14', '00:00:00', 'testing tour takeo province', 'bus', '10', '12', 1, 25, 1),
(15, 'Tour chhing 1', '2014-03-21', '09:00:00', 'Chhing House', 'bus', '25', '30', 1, 7, 0),
(16, 'Tour testing ggg', '2014-02-12', '10:00:00', 'sdf', 'bus', '35', '26', 2, 25, 1),
(17, 'PP - Svay Rieng', '2014-02-13', '06:00:00', 'sdfsds', 'bus', '35', '26', 3, 36, 0),
(18, 'This is my testing', '2014-03-13', '00:00:00', 'hello', 'bus', '10', '13', 1, 6, 0),
(19, 'This is my testing2', '2014-03-13', '00:00:00', 'Hello wrold', 'bus', '12', '14', NULL, 25, 0),
(20, 'kakaka', '1983-03-24', '09:00:00', 'Hello world, him', 'taxi', '10', '15', 9, 40, 1);

-- --------------------------------------------------------

--
-- Table structure for table `cgate_transports`
--

CREATE TABLE IF NOT EXISTS `cgate_transports` (
  `transport_id` int(11) NOT NULL AUTO_INCREMENT,
  `company_name` varchar(100) NOT NULL,
  `taxi_name` varchar(100) NOT NULL,
  `taxi_fname` varchar(100) NOT NULL,
  `taxi_lname` varchar(100) NOT NULL,
  `phone` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `mark` varchar(250) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `deleted` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`transport_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=60 ;

--
-- Dumping data for table `cgate_transports`
--

INSERT INTO `cgate_transports` (`transport_id`, `company_name`, `taxi_name`, `taxi_fname`, `taxi_lname`, `phone`, `mark`, `deleted`) VALUES
(57, 'drive bus', 'sreychen sokha', '', '', '0972792242', 'She is good taxi for driving the big bus', 0),
(58, 'chhingla', 'chhingla hang', 'chhingla', 'han', '0972792243', 'Hello world', 0),
(59, 'chen', '', 'sok', 'chensrey', '0972792249', 'Hello cheng', 1);

-- --------------------------------------------------------

--
-- Table structure for table `cgate_visas_types`
--

CREATE TABLE IF NOT EXISTS `cgate_visas_types` (
  `visa_type_id` int(10) NOT NULL AUTO_INCREMENT,
  `visa_type_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `deleted` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`visa_type_id`),
  KEY `deleted` (`deleted`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cgate_customers`
--
ALTER TABLE `cgate_customers`
  ADD CONSTRAINT `fk_cgate_customer_people` FOREIGN KEY (`customer_id`) REFERENCES `cgate_people` (`person_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `cgate_detail_orders_bikes`
--
ALTER TABLE `cgate_detail_orders_bikes`
  ADD CONSTRAINT `fk_cgate_detail_orders_bikes_cgate_orders1` FOREIGN KEY (`orderID`) REFERENCES `cgate_orders` (`order_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_detail_orders_bikes_items_bikes1` FOREIGN KEY (`item_bikeID`) REFERENCES `cgate_items_bikes` (`item_bike_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `cgate_detail_orders_tickets`
--
ALTER TABLE `cgate_detail_orders_tickets`
  ADD CONSTRAINT `fk_cgate_detail_orders_tickets_cgate_orders1` FOREIGN KEY (`orderID`) REFERENCES `cgate_orders` (`order_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_cgate_detail_orders_tickets_cgate_tickets1` FOREIGN KEY (`ticketID`) REFERENCES `cgate_tickets` (`ticket_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `cgate_detail_orders_tours`
--
ALTER TABLE `cgate_detail_orders_tours`
  ADD CONSTRAINT `fk_cgate_detail_orders_tickets_cgate_orders10` FOREIGN KEY (`orderID`) REFERENCES `cgate_orders` (`order_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_cgate_detail_orders_tours_cgate_tours1` FOREIGN KEY (`tour_id`) REFERENCES `cgate_tours` (`tour_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `cgate_detail_orders_visas`
--
ALTER TABLE `cgate_detail_orders_visas`
  ADD CONSTRAINT `fk_cgate_detail_orders_visas_cgate_employees` FOREIGN KEY (`received_from_employee`) REFERENCES `cgate_employees` (`employee_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_cgate_detail_orders_visas_cgate_orders1` FOREIGN KEY (`orderID`) REFERENCES `cgate_orders` (`order_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_cgate_detail_orders_visas_cgate_suppliers1` FOREIGN KEY (`commisioner`) REFERENCES `cgate_suppliers` (`supplier_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_detail_orders_visas_visas_types1` FOREIGN KEY (`item_visa_typeID`) REFERENCES `cgate_visas_types` (`visa_type_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `cgate_employees`
--
ALTER TABLE `cgate_employees`
  ADD CONSTRAINT `cgate_employees_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `cgate_people` (`person_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_cgate_employees_cgate_positions1` FOREIGN KEY (`position_id`) REFERENCES `cgate_positions` (`position_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `cgate_exchange_money`
--
ALTER TABLE `cgate_exchange_money`
  ADD CONSTRAINT `fk_exchange_money_currency_types1` FOREIGN KEY (`currency_typeID`) REFERENCES `cgate_currency_types` (`currency_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_exchange_money_exchanges` FOREIGN KEY (`employeeID`) REFERENCES `cgate_employees` (`employee_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_exchange_money_offices` FOREIGN KEY (`received_from`) REFERENCES `cgate_offices` (`office_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `cgate_giftcards`
--
ALTER TABLE `cgate_giftcards`
  ADD CONSTRAINT `cgate_giftcards_ibfk_1` FOREIGN KEY (`customerID`) REFERENCES `cgate_customers` (`customer_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `cgate_inventory`
--
ALTER TABLE `cgate_inventory`
  ADD CONSTRAINT `fk_inventory_employees` FOREIGN KEY (`trans_user`) REFERENCES `cgate_employees` (`employee_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `cgate_items_massages`
--
ALTER TABLE `cgate_items_massages`
  ADD CONSTRAINT `cgate_items_massages_ibfk_11` FOREIGN KEY (`supplierID`) REFERENCES `cgate_suppliers` (`supplier_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_cgate_items_massages_cgate_massages_types1` FOREIGN KEY (`massage_typesID`) REFERENCES `cgate_massages_types` (`massage_type_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `cgate_item_kits_bikes`
--
ALTER TABLE `cgate_item_kits_bikes`
  ADD CONSTRAINT `fk_cgate_item_kits_bikes_cgate_item_bikes` FOREIGN KEY (`item_bike_id`) REFERENCES `cgate_items_bikes` (`item_bike_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_cgate_item_kits_bikes_cgate_item_kits` FOREIGN KEY (`item_kit_id`) REFERENCES `cgate_item_kits` (`item_kit_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `cgate_item_kits_taxes`
--
ALTER TABLE `cgate_item_kits_taxes`
  ADD CONSTRAINT `cgate_item_kits_taxes_ibfk_1` FOREIGN KEY (`item_kit_id`) REFERENCES `cgate_item_kits` (`item_kit_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `cgate_item_kits_tickets`
--
ALTER TABLE `cgate_item_kits_tickets`
  ADD CONSTRAINT `fk_cgate_item_kits_tickets_cgate_item_kits` FOREIGN KEY (`item_kit_id`) REFERENCES `cgate_item_kits` (`item_kit_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_cgate_item_kits_tickets_cgate_tickets` FOREIGN KEY (`ticket_id`) REFERENCES `cgate_tickets` (`ticket_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `cgate_item_kits_tours`
--
ALTER TABLE `cgate_item_kits_tours`
  ADD CONSTRAINT `fk_item_kit_tour_item_kit` FOREIGN KEY (`item_kit_id`) REFERENCES `cgate_item_kits` (`item_kit_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_item_kit_tour_tour_id` FOREIGN KEY (`tour_id`) REFERENCES `cgate_tours` (`tour_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `cgate_item_kit_items`
--
ALTER TABLE `cgate_item_kit_items`
  ADD CONSTRAINT `cgate_item_kit_items_ibfk_1` FOREIGN KEY (`item_kit_id`) REFERENCES `cgate_item_kits` (`item_kit_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_cgate_item_kit_items_cgate_items_bikes1` FOREIGN KEY (`item_id`) REFERENCES `cgate_items_bikes` (`item_bike_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `cgate_modules_actions`
--
ALTER TABLE `cgate_modules_actions`
  ADD CONSTRAINT `cgate_modules_actions_ibfk_1` FOREIGN KEY (`module_id`) REFERENCES `cgate_modules` (`module_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `cgate_orders`
--
ALTER TABLE `cgate_orders`
  ADD CONSTRAINT `cgate_orders_ibfk_200` FOREIGN KEY (`customer_id`) REFERENCES `cgate_customers` (`customer_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_cgate_orders_cgate_commissioner` FOREIGN KEY (`commisioner_id`) REFERENCES `cgate_commissioners` (`commisioner_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_cgate_orders_cgate_employees` FOREIGN KEY (`employee_id`) REFERENCES `cgate_employees` (`employee_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_cgate_orders_cgate_offices1` FOREIGN KEY (`received_from`) REFERENCES `cgate_offices` (`office_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_orders_tours_guides1` FOREIGN KEY (`guide_id`) REFERENCES `cgate_guides` (`guide_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `cgate_orders_item_kits`
--
ALTER TABLE `cgate_orders_item_kits`
  ADD CONSTRAINT `cgate_orders_item_kits_ibfk_1` FOREIGN KEY (`item_kitID`) REFERENCES `cgate_item_kits` (`item_kit_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `sale_id_order_id` FOREIGN KEY (`sale_id`) REFERENCES `cgate_orders` (`order_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `cgate_permissions`
--
ALTER TABLE `cgate_permissions`
  ADD CONSTRAINT `cgate_permissions_ibfk_1` FOREIGN KEY (`person_id`) REFERENCES `cgate_employees` (`employee_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_permissions_cgate_modules` FOREIGN KEY (`module_id`) REFERENCES `cgate_modules` (`module_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_permissions_offices` FOREIGN KEY (`office_id`) REFERENCES `cgate_offices` (`office_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `cgate_permissions_actions`
--
ALTER TABLE `cgate_permissions_actions`
  ADD CONSTRAINT `cgate_permissions_actions_employees` FOREIGN KEY (`person_id`) REFERENCES `cgate_employees` (`employee_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `cgate_permissions_actions_ibfk_1` FOREIGN KEY (`module_id`) REFERENCES `cgate_modules` (`module_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `cgate_permissions_actions_ibfk_3` FOREIGN KEY (`action_id`) REFERENCES `cgate_modules_actions` (`action_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `cgate_permissions_office`
--
ALTER TABLE `cgate_permissions_office`
  ADD CONSTRAINT `fk_permissions_offices_in_offices` FOREIGN KEY (`office_id`) REFERENCES `cgate_offices` (`office_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_permissions_person` FOREIGN KEY (`person_id`) REFERENCES `cgate_employees` (`employee_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `cgate_receivings`
--
ALTER TABLE `cgate_receivings`
  ADD CONSTRAINT `cgate_receivings_ibfk_1` FOREIGN KEY (`employeeID`) REFERENCES `cgate_employees` (`employee_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `cgate_receivings_ibfk_2` FOREIGN KEY (`supplierID`) REFERENCES `cgate_suppliers` (`supplier_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `cgate_receivings_items`
--
ALTER TABLE `cgate_receivings_items`
  ADD CONSTRAINT `cgate_receivings_items_ibfk_2` FOREIGN KEY (`receiving_id`) REFERENCES `cgate_receivings` (`receiving_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_phppos_receivings_items_items_bikes1` FOREIGN KEY (`item_id`) REFERENCES `cgate_items_bikes` (`item_bike_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_phppos_receivings_items_items_massages1` FOREIGN KEY (`item_id`) REFERENCES `cgate_items_massages` (`item_massage_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `cgate_register_logs`
--
ALTER TABLE `cgate_register_logs`
  ADD CONSTRAINT `fk_register_logs_employees` FOREIGN KEY (`employeeID`) REFERENCES `cgate_employees` (`employee_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `cgate_sales_items_taxes`
--
ALTER TABLE `cgate_sales_items_taxes`
  ADD CONSTRAINT `fk_sales_items_taxes_orders_item_1` FOREIGN KEY (`sale_ticket_id`) REFERENCES `cgate_detail_orders_tickets` (`orderID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_sales_items_taxes_orders_ticket` FOREIGN KEY (`ticket_id`) REFERENCES `cgate_tickets` (`ticket_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `cgate_sales_item_kits_taxes`
--
ALTER TABLE `cgate_sales_item_kits_taxes`
  ADD CONSTRAINT `cgate_sales_item_kits_taxes_ibfk_2` FOREIGN KEY (`item_kitID`) REFERENCES `cgate_item_kits` (`item_kit_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_cgate_sales_item_kits_taxes_cgate_orders_item_kits1` FOREIGN KEY (`saleID`) REFERENCES `cgate_orders_item_kits` (`sale_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `cgate_sales_payments`
--
ALTER TABLE `cgate_sales_payments`
  ADD CONSTRAINT `fk_cgate_sales_payments_cgate_orders_tours1` FOREIGN KEY (`sale_id`) REFERENCES `cgate_orders` (`order_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `cgate_suppliers`
--
ALTER TABLE `cgate_suppliers`
  ADD CONSTRAINT `fk_suppliers_people` FOREIGN KEY (`supplier_id`) REFERENCES `cgate_people` (`person_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `cgate_suppliers_offices`
--
ALTER TABLE `cgate_suppliers_offices`
  ADD CONSTRAINT `fk_suppliers_offices_offices` FOREIGN KEY (`office_id`) REFERENCES `cgate_offices` (`office_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_suppliers_office_suppliers` FOREIGN KEY (`supplier_id`) REFERENCES `cgate_suppliers` (`supplier_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `cgate_tickets`
--
ALTER TABLE `cgate_tickets`
  ADD CONSTRAINT `fk_cgate_tickets_cgate_destinations1` FOREIGN KEY (`destinationID`) REFERENCES `cgate_destinations` (`destinate_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_cgate_tickets_cgate_tickets_types1` FOREIGN KEY (`ticket_typeID`) REFERENCES `cgate_tickets_types` (`ticket_type_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `cgate_tours`
--
ALTER TABLE `cgate_tours`
  ADD CONSTRAINT `fk_tour_destination_id` FOREIGN KEY (`destinationID`) REFERENCES `cgate_destinations` (`destinate_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_tour_suppliers` FOREIGN KEY (`supplier_id`) REFERENCES `cgate_suppliers` (`supplier_id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
