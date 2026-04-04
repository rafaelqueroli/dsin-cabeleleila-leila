-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 05/04/2026 às 00:17
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `bd-cabeleleila-leila`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `tbagendamentos`
--

CREATE TABLE `tbagendamentos` (
  `id` int(11) NOT NULL,
  `cliente_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `time_start` time NOT NULL,
  `time_end` time NOT NULL,
  `status` enum('pendente','confirmado','concluido','cancelado') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `tbagendamentos`
--

INSERT INTO `tbagendamentos` (`id`, `cliente_id`, `date`, `time_start`, `time_end`, `status`, `created_at`) VALUES
(26, 52, '2026-04-15', '08:30:00', '10:30:00', 'pendente', '2026-04-04 21:29:06'),
(27, 53, '2026-04-15', '12:45:00', '14:45:00', 'confirmado', '2026-04-04 21:50:11'),
(28, 54, '2026-04-11', '08:00:00', '10:30:00', 'cancelado', '2026-04-04 21:50:28'),
(29, 56, '2026-04-30', '10:45:00', '12:30:00', 'concluido', '2026-04-04 21:50:43');

-- --------------------------------------------------------

--
-- Estrutura para tabela `tbagendamentosservicos`
--

CREATE TABLE `tbagendamentosservicos` (
  `id` int(11) NOT NULL,
  `agendamento_id` int(11) NOT NULL,
  `servico_id` int(11) NOT NULL,
  `finalprice` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `tbagendamentosservicos`
--

INSERT INTO `tbagendamentosservicos` (`id`, `agendamento_id`, `servico_id`, `finalprice`) VALUES
(73, 26, 2, 150.00),
(74, 26, 3, 100.00),
(82, 28, 3, 100.00),
(83, 28, 4, 75.00),
(84, 28, 8, 200.00),
(85, 27, 2, 150.00),
(86, 27, 3, 100.00),
(87, 29, 6, 30.00),
(88, 29, 7, 50.00);

-- --------------------------------------------------------

--
-- Estrutura para tabela `tbservicos`
--

CREATE TABLE `tbservicos` (
  `id` int(11) NOT NULL,
  `cat` enum('c','u','e') NOT NULL,
  `name` varchar(255) NOT NULL,
  `duration_min` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `tbservicos`
--

INSERT INTO `tbservicos` (`id`, `cat`, `name`, `duration_min`, `price`) VALUES
(1, 'c', 'Corte', 45, 100.00),
(2, 'c', 'Coloração', 60, 150.00),
(3, 'c', 'Tratamento', 60, 100.00),
(4, 'c', 'Finalização', 30, 75.00),
(5, 'u', 'Manicure Simples', 45, 30.00),
(6, 'u', 'Pedicure Simples', 45, 30.00),
(7, 'e', 'Sombrancelha', 60, 50.00),
(8, 'e', 'Maquiagem', 60, 200.00);

-- --------------------------------------------------------

--
-- Estrutura para tabela `tbusuarios`
--

CREATE TABLE `tbusuarios` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `surname` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone_n` varchar(11) NOT NULL,
  `pass` varchar(255) NOT NULL,
  `role` enum('c','a') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `tbusuarios`
--

INSERT INTO `tbusuarios` (`id`, `name`, `surname`, `email`, `phone_n`, `pass`, `role`, `created_at`) VALUES
(51, 'Leila', 'Cabeleleila', 'cabeleleilaleila@gmail.com', '14997813412', '$2y$10$kQDrIoB15t4C3vpdXJBZz.Zm4JFyyHT/Skbm1kyhywp1mu3UnimLy', 'a', '2026-04-04 20:18:44'),
(52, 'Usuário', 'Cliente A', 'cliente.a@gmail.com', '11111111111', '$2y$10$.3aFspBnpwGRnHne1Bhbp..iBVudaQj6EJ.fku4rO8.5fNTPylWhK', 'c', '2026-04-04 20:21:35'),
(53, 'Usuário', 'Cliente B', 'cliente.b@gmail.com', '11111111114', '$2y$10$FiNIHlc0l2f.c49U/wn8UOQalolgAlMNutseIj26H9or0ubQtUliK', 'c', '2026-04-04 20:21:58'),
(54, 'Usuário', 'Cliente C', 'cliente.c@gmail.com', '11111111144', '$2y$10$rqSG7g/WtnQxtLsWwOoDTO5gAvR81JMKEd74ULJtadm2WhxWkTLRS', 'c', '2026-04-04 20:23:18'),
(55, 'Usuário', 'Cliente D', 'cliente.d@gmail.com', '11111111444', '$2y$10$MtuhaVYSoO6qKM9hwqiKc.ziU4ZOEHLkjb6zFKL8pj2Gu3hPtdT5q', 'c', '2026-04-04 20:25:44'),
(56, 'Usuário', 'Cliente E', 'cliente.e@gmail.com', '11111114444', '$2y$10$BZGl4c6JJD59qEIDQ9jFWeqj/QlHrS02ihv1.neQb.HlATmJRT/wS', 'c', '2026-04-04 20:26:09'),
(57, 'Usuario Admin', 'Provisório', 'admin@gmail.com', '22222222222', '$2y$10$9uSsyiPVG7IrlOtwBZjtwedeyBAahR15JYUieOlP7JBzyrzsTk3xq', 'a', '2026-04-04 20:35:33');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `tbagendamentos`
--
ALTER TABLE `tbagendamentos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_clientes` (`cliente_id`);

--
-- Índices de tabela `tbagendamentosservicos`
--
ALTER TABLE `tbagendamentosservicos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_agendamentos` (`agendamento_id`),
  ADD KEY `fk_servicos` (`servico_id`);

--
-- Índices de tabela `tbservicos`
--
ALTER TABLE `tbservicos`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `tbusuarios`
--
ALTER TABLE `tbusuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `tbagendamentos`
--
ALTER TABLE `tbagendamentos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT de tabela `tbagendamentosservicos`
--
ALTER TABLE `tbagendamentosservicos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=89;

--
-- AUTO_INCREMENT de tabela `tbservicos`
--
ALTER TABLE `tbservicos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de tabela `tbusuarios`
--
ALTER TABLE `tbusuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `tbagendamentos`
--
ALTER TABLE `tbagendamentos`
  ADD CONSTRAINT `fk_clientes` FOREIGN KEY (`cliente_id`) REFERENCES `tbusuarios` (`id`);

--
-- Restrições para tabelas `tbagendamentosservicos`
--
ALTER TABLE `tbagendamentosservicos`
  ADD CONSTRAINT `fk_agendamentos` FOREIGN KEY (`agendamento_id`) REFERENCES `tbagendamentos` (`id`),
  ADD CONSTRAINT `fk_servicos` FOREIGN KEY (`servico_id`) REFERENCES `tbservicos` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
