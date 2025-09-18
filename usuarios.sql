-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Tempo de geração: 18/09/2025 às 03:51
-- Versão do servidor: 12.0.2-MariaDB
-- Versão do PHP: 8.4.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `agenda03`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `data_nasc` date NOT NULL,
  `estado` varchar(2) NOT NULL,
  `endereco` varchar(255) NOT NULL,
  `sexo` varchar(10) NOT NULL,
  `login` varchar(50) NOT NULL,
  `senha` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `nome`, `email`, `data_nasc`, `estado`, `endereco`, `sexo`, `login`, `senha`) VALUES
(2, 'Allan teste', 'allan@teste.com', '1999-01-01', 'sc', 'rua dos alfeneiros', 'masculino', '123', '$2y$12$Dx9IULHujmxtT4DGvVCfzuCU4Khb5IKf/P4JKRMa7NdRuclFRuDL2'),
(3, 'novo teste', 'testando@123.com', '1990-01-01', 'pr', 'condado', 'feminino', '9999', '$2y$12$ZWti5wiMtY.sRb8ZEUorjOa36IpsSqz6O/JXJWa9IRWvdb0eHiS/i');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
