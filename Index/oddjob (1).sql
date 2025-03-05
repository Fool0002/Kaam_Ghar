-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 18, 2024 at 05:05 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `oddjob`
--

-- --------------------------------------------------------

--
-- Table structure for table `applied_job`
--

CREATE TABLE `applied_job` (
  `id` int(10) NOT NULL,
  `job_title` varchar(255) NOT NULL,
  `employer_email` varchar(255) NOT NULL,
  `applied_by` varchar(255) NOT NULL,
  `status` varchar(50) NOT NULL,
  `select_status` varchar(50) NOT NULL DEFAULT 'pending',
  `del` varchar(5) DEFAULT 'false',
  `app_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `applied_job`
--

INSERT INTO `applied_job` (`id`, `job_title`, `employer_email`, `applied_by`, `status`, `select_status`, `del`, `app_id`) VALUES
(13, 'Graphic Designer', 'employer@gmail.com', 'ramal@gmail.com', 'applied', 'pending', 'false', 1),
(4, 'Data Analyst', 'kailash@gmail.com', 'ramal@gmail.com', 'pending', 'pending', 'true', 2),
(2, 'Marketing Specialist', 'himal@gmail.com', 'ramal@gmail.com', 'applied', 'pending', 'true', 3),
(3, 'Graphic Designer', 'employer@gmail.com', 'ramal@gmail.com', 'applied', 'pending', 'true', 4),
(11, 'Software Engineer', 'ramu@gmail.com', 'ramal@gmail.com', 'pending', 'rejected', 'true', 5),
(25, 'HR Manager', 'saroj@gmail.com', 'hari@gmail.com', 'applied', 'pending', 'false', 6),
(1, 'Software Engineer', 'ramu@gmail.com', 'ram@gmail.com', 'applied', 'selected', 'true', 7),
(2, 'Marketing Specialist', 'himal@gmail.com', 'ram@gmail.com', 'applied', 'pending', 'true', 8),
(3, 'Graphic Designer', 'employer@gmail.com', 'ram@gmail.com', 'applied', 'pending', 'true', 9),
(4, 'Data Analyst', 'kailash@gmail.com', 'ram@gmail.com', 'pending', 'pending', 'true', 10),
(6, 'Project Manager', 'ramu@gmail.com', 'ram@gmail.com', 'pending', 'pending', 'true', 11),
(11, 'Software Engineer', 'ramu@gmail.com', 'ram@gmail.com', 'pending', 'pending', 'true', 12),
(12, 'Marketing Specialist', 'himal@gmail.com', 'ram@gmail.com', 'pending', 'pending', 'true', 13);

-- --------------------------------------------------------

--
-- Table structure for table `cv`
--

CREATE TABLE `cv` (
  `id` int(11) NOT NULL,
  `first_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `age` int(11) NOT NULL,
  `gender` varchar(10) NOT NULL,
  `profile_picture` varchar(255) DEFAULT NULL,
  `experience` int(11) DEFAULT NULL,
  `job_title` varchar(100) DEFAULT NULL,
  `degree` varchar(100) DEFAULT NULL,
  `university` varchar(100) DEFAULT NULL,
  `grad_year` int(11) DEFAULT NULL,
  `skills` text DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `del` varchar(5) DEFAULT 'false'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cv`
--

INSERT INTO `cv` (`id`, `first_name`, `last_name`, `age`, `gender`, `profile_picture`, `experience`, `job_title`, `degree`, `university`, `grad_year`, `skills`, `email`, `del`) VALUES
(1, 'Hero', 'hirakat', 24, 'male', 'profile_pictures/2b83ec95728597e7f87e8dbac7b3422b.jpg', 4, 'I.T', '+2', 'Pashupati Multiple Campus', 2020, 'IT developer, Mern Stack', 'ram@gmail.com', 'false');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `job_title` varchar(255) NOT NULL,
  `job_description` text NOT NULL,
  `company_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `location` varchar(255) NOT NULL,
  `employment_type` varchar(255) NOT NULL,
  `salary` varchar(255) DEFAULT NULL,
  `required_experience` varchar(255) DEFAULT NULL,
  `education_level` varchar(255) DEFAULT NULL,
  `skills` text DEFAULT NULL,
  `application_deadline` date DEFAULT NULL,
  `benefits` text DEFAULT NULL,
  `company_overview` text DEFAULT NULL,
  `contact_information` text DEFAULT NULL,
  `category_name` varchar(255) NOT NULL,
  `status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `del` varchar(5) DEFAULT 'false'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jobs`
--

INSERT INTO `jobs` (`id`, `user_id`, `job_title`, `job_description`, `company_name`, `email`, `location`, `employment_type`, `salary`, `required_experience`, `education_level`, `skills`, `application_deadline`, `benefits`, `company_overview`, `contact_information`, `category_name`, `status`, `created_at`, `updated_at`, `del`) VALUES
(1, 1, 'Software Engineer', 'Develop and maintain software applications.', 'Tech Solutions', 'ramu@gmail.com', 'New York, NY', 'Full-time', '$80,000 - $100,000', '3+ years', 'Bachelor\'s Degree', 'Java, Python, SQL', '2024-12-31', 'Health insurance, 401(k)', 'A leading tech company.', '123-456-7890', 'Technology', 'approved', '2024-07-10 08:00:50', '2024-07-10 08:04:39', 'false'),
(2, 4, 'Marketing Specialist', 'Plan and execute marketing campaigns.', 'Marketing Experts', 'himal@gmail.com', 'Los Angeles, CA', 'Full-time', '$50,000 - $70,000', '2+ years', 'Bachelor\'s Degree', 'SEO, SEM, Content Marketing', '2024-11-30', 'Health insurance, Paid time off', 'A premier marketing firm.', '987-654-3210', 'Marketing', 'approved', '2024-07-10 08:00:50', '2024-07-13 04:24:23', 'false'),
(3, 3, 'Graphic Designer', 'Create visual concepts to communicate ideas.', 'Creative Agency', 'employer@gmail.com', 'San Francisco, CA', 'Part-time', '$40,000 - $60,000', '1+ years', 'Bachelor\'s Degree', 'Adobe Photoshop, Illustrator', '2024-10-31', 'Flexible hours', 'A top design agency.', '456-789-1234', 'Design', 'approved', '2024-07-10 08:00:50', '2024-07-10 08:04:42', 'false'),
(4, 4, 'Data Analyst', 'Analyze data to assist decision-making.', 'Data Insights', 'kailash@gmail.com', 'Chicago, IL', 'Full-time', '$70,000 - $90,000', '2+ years', 'Bachelor\'s Degree', 'Excel, SQL, R', '2024-09-30', 'Health insurance, Paid time off', 'A data-driven company.', '321-654-9870', 'Data Analysis', 'approved', '2024-07-10 08:00:50', '2024-07-10 08:04:42', 'false'),
(5, 5, 'HR Manager', 'Manage human resources activities.', 'HR Solutions', 'saroj@gmail.com', 'Boston, MA', 'Full-time', '$60,000 - $80,000', '5+ years', 'Master\'s Degree', 'Recruitment, Employee Relations', '2024-08-31', 'Health insurance, 401(k)', 'An HR consulting firm.', '654-321-0987', 'Human Resources', 'approved', '2024-07-10 08:00:50', '2024-07-10 08:04:42', 'false'),
(6, 6, 'Project Manager', 'Lead and manage projects.', 'Project Leaders', 'ramu@gmail.com', 'Austin, TX', 'Full-time', '$90,000 - $110,000', '3+ years', 'Bachelor\'s Degree', 'Project Management, Agile', '2024-07-31', 'Health insurance, Paid time off', 'A project management company.', '789-123-4567', 'Project Management', 'approved', '2024-07-10 08:00:50', '2024-07-10 08:04:43', 'false'),
(11, 11, 'Software Engineer', 'Develop and maintain software applications.', 'Tech Solutions', 'ramu@gmail.com', 'New York, NY', 'Full-time', '$80,000 - $100,000', '3+ years', 'Bachelor\'s Degree', 'Java, Python, SQL', '2024-12-31', 'Health insurance, 401(k)', 'A leading tech company.', '123-456-7890', 'Technology', 'approved', '2024-07-10 08:00:50', '2024-07-10 08:04:43', 'false'),
(12, 4, 'Marketing Specialist', 'Plan and execute marketing campaigns.', 'Marketing Experts', 'himal@gmail.com', 'Los Angeles, CA', 'Full-time', '$50,000 - $70,000', '2+ years', 'Bachelor\'s Degree', 'SEO, SEM, Content Marketing', '2024-11-30', 'Health insurance, Paid time off', 'A premier marketing firm.', '987-654-3210', 'Marketing', 'approved', '2024-07-10 08:00:50', '2024-07-13 04:24:23', 'false'),
(13, 13, 'Graphic Designer', 'Create visual concepts to communicate ideas.', 'Creative Agency', 'employer@gmail.com', 'San Francisco, CA', 'Part-time', '$40,000 - $60,000', '1+ years', 'Bachelor\'s Degree', 'Adobe Photoshop, Illustrator', '2024-10-31', 'Flexible hours', 'A top design agency.', '456-789-1234', 'Design', 'approved', '2024-07-10 08:00:50', '2024-07-10 08:04:44', 'false'),
(14, 14, 'Data Analyst', 'Analyze data to assist decision-making.', 'Data Insights', 'kailash@gmail.com', 'Chicago, IL', 'Full-time', '$70,000 - $90,000', '2+ years', 'Bachelor\'s Degree', 'Excel, SQL, R', '2024-09-30', 'Health insurance, Paid time off', 'A data-driven company.', '321-654-9870', 'Data Analysis', 'approved', '2024-07-10 08:00:50', '2024-07-10 08:04:44', 'false'),
(15, 15, 'HR Manager', 'Manage human resources activities.', 'HR Solutions', 'saroj@gmail.com', 'Boston, MA', 'Full-time', '$60,000 - $80,000', '5+ years', 'Master\'s Degree', 'Recruitment, Employee Relations', '2024-08-31', 'Health insurance, 401(k)', 'An HR consulting firm.', '654-321-0987', 'Human Resources', 'approved', '2024-07-10 08:00:50', '2024-07-10 08:04:44', 'false'),
(16, 16, 'Project Manager', 'Lead and manage projects.', 'Project Leaders', 'ramu@gmail.com', 'Austin, TX', 'Full-time', '$90,000 - $110,000', '3+ years', 'Bachelor\'s Degree', 'Project Management, Agile', '2024-07-31', 'Health insurance, Paid time off', 'A project management company.', '789-123-4567', 'Project Management', 'approved', '2024-07-10 08:00:50', '2024-07-10 08:04:45', 'false'),
(21, 21, 'Software Engineer', 'Develop and maintain software applications.', 'Tech Solutions', 'ramu@gmail.com', 'New York, NY', 'Full-time', '$80,000 - $100,000', '3+ years', 'Bachelor\'s Degree', 'Java, Python, SQL', '2024-12-31', 'Health insurance, 401(k)', 'A leading tech company.', '123-456-7890', 'Technology', 'approved', '2024-07-10 08:00:50', '2024-07-10 08:04:45', 'false'),
(22, 4, 'Marketing Specialist', 'Plan and execute marketing campaigns.', 'Marketing Experts', 'himal@gmail.com', 'Los Angeles, CA', 'Full-time', '$50,000 - $70,000', '2+ years', 'Bachelor\'s Degree', 'SEO, SEM, Content Marketing', '2024-11-30', 'Health insurance, Paid time off', 'A premier marketing firm.', '987-654-3210', 'Marketing', 'approved', '2024-07-10 08:00:50', '2024-07-13 04:24:23', 'false'),
(23, 23, 'Graphic Designer', 'Create visual concepts to communicate ideas.', 'Creative Agency', 'employer@gmail.com', 'San Francisco, CA', 'Part-time', '$40,000 - $60,000', '1+ years', 'Bachelor\'s Degree', 'Adobe Photoshop, Illustrator', '2024-10-31', 'Flexible hours', 'A top design agency.', '456-789-1234', 'Design', 'approved', '2024-07-10 08:00:50', '2024-07-10 08:04:46', 'false'),
(24, 24, 'Data Analyst', 'Analyze data to assist decision-making.', 'Data Insights', 'kailash@gmail.com', 'Chicago, IL', 'Full-time', '$70,000 - $90,000', '2+ years', 'Bachelor\'s Degree', 'Excel, SQL, R', '2024-09-30', 'Health insurance, Paid time off', 'A data-driven company.', '321-654-9870', 'Data Analysis', 'approved', '2024-07-10 08:00:50', '2024-07-10 08:04:46', 'false'),
(25, 25, 'HR Manager', 'Manage human resources activities.', 'HR Solutions', 'saroj@gmail.com', 'Boston, MA', 'Full-time', '$60,000 - $80,000', '5+ years', 'Master\'s Degree', 'Recruitment, Employee Relations', '2024-08-31', 'Health insurance, 401(k)', 'An HR consulting firm.', '654-321-0987', 'Human Resources', 'approved', '2024-07-10 08:00:50', '2024-07-10 08:04:47', 'false'),
(26, 26, 'Project Manager', 'Lead and manage projects.', 'Project Leaders', 'ramu@gmail.com', 'Austin, TX', 'Full-time', '$90,000 - $110,000', '3+ years', 'Bachelor\'s Degree', 'Project Management, Agile', '2024-07-31', 'Health insurance, Paid time off', 'A project management company.', '789-123-4567', 'Project Management', 'approved', '2024-07-10 08:00:50', '2024-07-10 08:04:48', 'false'),
(31, 31, 'Software Engineer', 'Develop and maintain software applications.', 'Tech Solutions', 'ramu@gmail.com', 'New York, NY', 'Full-time', '$80,000 - $100,000', '3+ years', 'Bachelor\'s Degree', 'Java, Python, SQL', '2024-12-31', 'Health insurance, 401(k)', 'A leading tech company.', '123-456-7890', 'Technology', 'approved', '2024-07-10 08:00:50', '2024-07-10 08:04:48', 'false'),
(32, 4, 'Marketing Specialist', 'Plan and execute marketing campaigns.', 'Marketing Experts', 'himal@gmail.com', 'Los Angeles, CA', 'Full-time', '$50,000 - $70,000', '2+ years', 'Bachelor\'s Degree', 'SEO, SEM, Content Marketing', '2024-11-30', 'Health insurance, Paid time off', 'A premier marketing firm.', '987-654-3210', 'Marketing', 'approved', '2024-07-10 08:00:50', '2024-07-13 04:24:23', 'false'),
(33, 33, 'Graphic Designer', 'Create visual concepts to communicate ideas.', 'Creative Agency', 'employer@gmail.com', 'San Francisco, CA', 'Part-time', '$40,000 - $60,000', '1+ years', 'Bachelor\'s Degree', 'Adobe Photoshop, Illustrator', '2024-10-31', 'Flexible hours', 'A top design agency.', '456-789-1234', 'Design', 'rejected', '2024-07-10 08:00:50', '2024-07-10 08:04:50', 'false'),
(34, 34, 'Data Analyst', 'Analyze data to assist decision-making.', 'Data Insights', 'kailash@gmail.com', 'Chicago, IL', 'Full-time', '$70,000 - $90,000', '2+ years', 'Bachelor\'s Degree', 'Excel, SQL, R', '2024-09-30', 'Health insurance, Paid time off', 'A data-driven company.', '321-654-9870', 'Data Analysis', 'rejected', '2024-07-10 08:00:50', '2024-07-10 08:04:52', 'false'),
(35, 35, 'HR Manager', 'Manage human resources activities.', 'HR Solutions', 'saroj@gmail.com', 'Boston, MA', 'Full-time', '$60,000 - $80,000', '5+ years', 'Master\'s Degree', 'Recruitment, Employee Relations', '2024-08-31', 'Health insurance, 401(k)', 'An HR consulting firm.', '654-321-0987', 'Human Resources', 'approved', '2024-07-10 08:00:50', '2024-07-10 08:04:51', 'false'),
(36, 36, 'Project Manager', 'Lead and manage projects.', 'Project Leaders', 'ramu@gmail.com', 'Austin, TX', 'Full-time', '$90,000 - $110,000', '3+ years', 'Bachelor\'s Degree', 'Project Management, Agile', '2024-07-31', 'Health insurance, Paid time off', 'A project management company.', '789-123-4567', 'Project Management', 'rejected', '2024-07-10 08:00:50', '2024-07-10 08:04:54', 'false'),
(41, 41, 'Software Engineer', 'Develop and maintain software applications.', 'Tech Solutions', 'ramu@gmail.com', 'New York, NY', 'Full-time', '$80,000 - $100,000', '3+ years', 'Bachelor\'s Degree', 'Java, Python, SQL', '2024-12-31', 'Health insurance, 401(k)', 'A leading tech company.', '123-456-7890', 'Technology', 'approved', '2024-07-10 08:00:50', '2024-07-10 08:04:53', 'false'),
(42, 4, 'Marketing Specialist', 'Plan and execute marketing campaigns.', 'Marketing Experts', 'himal@gmail.com', 'Los Angeles, CA', 'Full-time', '$50,000 - $70,000', '2+ years', 'Bachelor\'s Degree', 'SEO, SEM, Content Marketing', '2024-11-30', 'Health insurance, Paid time off', 'A premier marketing firm.', '987-654-3210', 'Marketing', 'approved', '2024-07-10 08:00:50', '2024-07-13 04:24:23', 'false'),
(43, 43, 'Graphic Designer', 'Create visual concepts to communicate ideas.', 'Creative Agency', 'employer@gmail.com', 'San Francisco, CA', 'Part-time', '$40,000 - $60,000', '1+ years', 'Bachelor\'s Degree', 'Adobe Photoshop, Illustrator', '2024-10-31', 'Flexible hours', 'A top design agency.', '456-789-1234', 'Design', 'rejected', '2024-07-10 08:00:50', '2024-07-10 08:04:55', 'false'),
(44, 44, 'Data Analyst', 'Analyze data to assist decision-making.', 'Data Insights', 'kailash@gmail.com', 'Chicago, IL', 'Full-time', '$70,000 - $90,000', '2+ years', 'Bachelor\'s Degree', 'Excel, SQL, R', '2024-09-30', 'Health insurance, Paid time off', 'A data-driven company.', '321-654-9870', 'Data Analysis', 'approved', '2024-07-10 08:00:50', '2024-07-10 08:04:57', 'false'),
(45, 45, 'HR Manager', 'Manage human resources activities.', 'HR Solutions', 'saroj@gmail.com', 'Boston, MA', 'Full-time', '$60,000 - $80,000', '5+ years', 'Master\'s Degree', 'Recruitment, Employee Relations', '2024-08-31', 'Health insurance, 401(k)', 'An HR consulting firm.', '654-321-0987', 'Human Resources', 'rejected', '2024-07-10 08:00:50', '2024-07-10 08:04:55', 'false'),
(46, 46, 'Project Manager', 'Lead and manage projects.', 'Project Leaders', 'ramu@gmail.com', 'Austin, TX', 'Full-time', '$90,000 - $110,000', '3+ years', 'Bachelor\'s Degree', 'Project Management, Agile', '2024-07-31', 'Health insurance, Paid time off', 'A project management company.', '789-123-4567', 'Project Management', 'approved', '2024-07-10 08:00:50', '2024-07-10 08:04:56', 'false'),
(52, 4, 'hari krishna', 'asdasd', 'asdas', 'himal@gmail.com', 'asdasd', 'Full-time', '2213', '4', '+2', 'asdasd', '2024-07-22', 'asdasd', 'asdasd', '8457968754', 'Writing', 'approved', '2024-07-13 07:31:56', '2024-07-13 07:32:26', 'false'),
(54, 4, 'asdasd', 'asdasd', 'asdasd', 'himal@gmail.com', 'asdasd', 'Full-time', '213', '2', 'SEE', 'asdasd', '2024-07-19', 'asdasd', 'asdas', '8457968754', 'Electronic', 'rejected', '2024-07-13 08:05:13', '2024-07-13 08:05:26', 'false'),
(56, 8, 'test job', 'sssssssssssssssssssssss', 'asdasd', 'Kailash@gmail.com', 'asda', 'Part-time', '60000', '5', '+2', 'asdasd', '2024-07-24', 'asdasd', 'asdasd', '1234567890', 'Data Analysis', 'approved', '2024-07-13 15:04:16', '2024-07-13 15:04:39', 'false');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `user_id` varchar(50) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `status` varchar(20) DEFAULT 'unread',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `message`, `status`, `created_at`) VALUES
(1, 'kailash@gmail.com', 'ram has applied for your job titled \'Data Analyst\'', 'unread', '2024-07-18 12:52:02'),
(2, 'ramu@gmail.com', 'ram has applied for your job titled \'Project Manager\'', 'unread', '2024-07-18 13:20:21'),
(3, 'ramu@gmail.com', 'ram has applied for your job titled \'Software Engineer\'', 'unread', '2024-07-18 13:27:30'),
(4, 'himal@gmail.com', 'ram has applied for your job titled \'Marketing Specialist\'', 'unread', '2024-07-18 13:32:21');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(50) NOT NULL,
  `del` varchar(5) DEFAULT 'false'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `del`) VALUES
(1, 'Nabin', 'Nabin@example.com', '12345678', 'admin', 'false'),
(2, 'ram', 'ram@gmail.com', 'Helloworld', 'employee', 'false'),
(3, 'ramu', 'ramu@gmail.com', '12345678', 'employer', 'false'),
(4, 'himal', 'himal@gmail.com', 'Himal123', 'employer', 'false'),
(5, 'ramal', 'ramal@gmail.com', '12345678', 'employee', 'false'),
(6, 'employer', 'employer@gmail.com', '12345678', 'employer', 'false'),
(7, 'saroj', 'saroj@gmail.com', '12345678', 'employer', 'false'),
(8, 'kailash', 'kailash@gmail.com', '12345678', 'employer', 'false'),
(9, 'harihari', 'hari@gmail.com', 'harihari', 'employee', 'false');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `applied_job`
--
ALTER TABLE `applied_job`
  ADD PRIMARY KEY (`app_id`),
  ADD KEY `fk_applied_jobs_applicant_email` (`applied_by`),
  ADD KEY `fk_applied_jobs_job_poster_email` (`employer_email`);

--
-- Indexes for table `cv`
--
ALTER TABLE `cv`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_cv_user_email` (`email`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_jobs_user_email` (`email`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_notifications_user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `applied_job`
--
ALTER TABLE `applied_job`
  MODIFY `app_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `cv`
--
ALTER TABLE `cv`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `applied_job`
--
ALTER TABLE `applied_job`
  ADD CONSTRAINT `fk_applied_jobs_applicant_email` FOREIGN KEY (`applied_by`) REFERENCES `users` (`email`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_applied_jobs_job_poster_email` FOREIGN KEY (`employer_email`) REFERENCES `users` (`email`) ON UPDATE CASCADE;

--
-- Constraints for table `cv`
--
ALTER TABLE `cv`
  ADD CONSTRAINT `fk_cv_user_email` FOREIGN KEY (`email`) REFERENCES `users` (`email`) ON UPDATE CASCADE;

--
-- Constraints for table `jobs`
--
ALTER TABLE `jobs`
  ADD CONSTRAINT `fk_jobs_user_email` FOREIGN KEY (`email`) REFERENCES `users` (`email`) ON UPDATE CASCADE;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `fk_notifications_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`email`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
