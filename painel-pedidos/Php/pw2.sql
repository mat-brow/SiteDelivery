-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 23-Set-2026
-- Versão do servidor: 10.4.21-MariaDB
-- versão do PHP: 7.3.31

CREATE DATABASE IF NOT EXISTS `pw2` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `pw2`;

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `pw2`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `pedidos`
--

CREATE TABLE `pedidos` (
  `id` int(11) NOT NULL,
  `cliente` varchar(100) NOT NULL,
  `itens` text NOT NULL,
  `valor_total` decimal(10,2) NOT NULL DEFAULT 0.00,
  `status` enum('pendente','preparando','entregue') NOT NULL DEFAULT 'pendente',
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `pedidos`
--

INSERT INTO `pedidos` (`id`, `cliente`, `itens`, `valor_total`, `status`, `criado_em`) VALUES
(1, 'João Silva', '2x Pizza calabresa\n1x Refrigerante 2L', 89.90, 'entregue', '2026-09-22 19:10:00'),
(2, 'Maria Souza', '1x Hambúrguer artesanal\n1x Batata frita\n1x Suco de laranja', 54.50, 'entregue', '2026-09-22 20:05:00'),
(3, 'Marcelo Lima', '3x Esfiha de carne\n2x Esfiha de queijo', 32.00, 'preparando', '2026-09-23 11:40:00'),
(4, 'Ana Paula', '1x Combo sushi 20 peças\n1x Yakisoba', 112.00, 'preparando', '2026-09-23 12:15:00'),
(5, 'Carlos Mendes', '1x Marmita fitness\n1x Água sem gás', 28.90, 'pendente', '2026-09-23 12:30:00');

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
