-- 1. Initialize the Database
-- Requirement: Drop and Create must be the first two lines
DROP DATABASE IF EXISTS conferenceDB;
CREATE DATABASE conferenceDB;
USE conferenceDB;

-- 2. Create Infrastructure Tables
CREATE TABLE HOTEL_ROOM (
    room_number INT PRIMARY KEY,
    number_of_beds INT NOT NULL CHECK (number_of_beds BETWEEN 1 AND 3)
);

CREATE TABLE COMPANY (
    company_name VARCHAR(100) PRIMARY KEY,
    sponsor_level ENUM('Platinum', 'Gold', 'Silver', 'Bronze') NOT NULL,
    sponsorship_amount DECIMAL(10, 2) NOT NULL,
    emails_available INT DEFAULT 0
);

CREATE TABLE SUB_COMMITTEE (
    committee_name VARCHAR(100) PRIMARY KEY
);

-- 3. Create People Tables
CREATE TABLE SPEAKER (
    speaker_id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    biography TEXT
);

CREATE TABLE ATTENDEE (
    attendee_id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    total_paid DECIMAL(10, 2) NOT NULL DEFAULT 0.00,
    attendee_type ENUM('Student', 'Professional', 'Sponsor') NOT NULL,
    company_name VARCHAR(100),
    room_number INT,
    FOREIGN KEY (company_name) REFERENCES COMPANY(company_name) ON DELETE CASCADE,
    FOREIGN KEY (room_number) REFERENCES HOTEL_ROOM(room_number) ON DELETE SET NULL
);

CREATE TABLE COMMITTEE_MEMBER (
    member_id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    committee_name VARCHAR(100),
    FOREIGN KEY (committee_name) REFERENCES SUB_COMMITTEE(committee_name) ON DELETE CASCADE
);

-- 4. Create Logistics Tables
CREATE TABLE SESSION (
    session_id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(100) NOT NULL,
    start_time TIME NOT NULL,
    end_time TIME NOT NULL,
    session_date DATE NOT NULL,
    location VARCHAR(100) NOT NULL,
    speaker_id INT,
    FOREIGN KEY (speaker_id) REFERENCES SPEAKER(speaker_id) ON DELETE SET NULL
);

CREATE TABLE JOB_AD (
    job_id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(100) NOT NULL,
    pay_rate DECIMAL(10, 2),
    city VARCHAR(50),
    company_name VARCHAR(100) NOT NULL,
    FOREIGN KEY (company_name) REFERENCES COMPANY(company_name) ON DELETE CASCADE
);

-- 5. Sample Data Population
-- Insert Hotel Rooms
INSERT INTO HOTEL_ROOM VALUES (101, 2), (102, 1), (103, 3), (201, 2);

-- Insert Companies
INSERT INTO COMPANY VALUES ('Bionic-Tech', 'Platinum', 10000.00, 10);
INSERT INTO COMPANY VALUES ('Limb-Logic', 'Gold', 5000.00, 5);
INSERT INTO COMPANY VALUES ('Kingston-Health', 'Silver', 2500.00, 2);

-- Insert Sub-Committees
INSERT INTO SUB_COMMITTEE VALUES ('Technical'), ('Registration'), ('Social'), ('Finance');

-- Insert Committee Members
INSERT INTO COMMITTEE_MEMBER (first_name, last_name, committee_name) VALUES 
('Emese', 'Elkind', 'Technical'),
('John', 'Doe', 'Technical'),
('Jane', 'Smith', 'Registration'),
('Alan', 'Turing', 'Finance');

-- Insert Speakers
INSERT INTO SPEAKER (first_name, last_name, biography) VALUES 
('Sarah', 'Connor', 'Expert in cybernetic integration.'),
('Victor', 'Frankenstein', 'Pioneer in biological structural engineering.');

-- Insert Sessions
INSERT INTO SESSION (title, start_time, end_time, session_date, location, speaker_id) VALUES 
('Neural Interface 101', '09:00:00', '10:30:00', '2026-04-05', 'Ballroom A', 1),
('Carbon Fiber Casting', '11:00:00', '12:30:00', '2026-04-05', 'Lab 2', 2),
('Future of Prosthetics', '14:00:00', '15:30:00', '2026-04-06', 'Ballroom A', 1);

-- Insert Job Ads
INSERT INTO JOB_AD (title, pay_rate, city, company_name) VALUES 
('Junior Engineer', 75000, 'Kingston', 'Bionic-Tech'),
('Sales Lead', 60000, 'Toronto', 'Limb-Logic'),
('Research Assistant', 55000, 'Kingston', 'Bionic-Tech');

-- Insert Attendees (Mixture of Students, Pros, and Sponsors)
-- Students (paying 50.00) assigned to rooms
INSERT INTO ATTENDEE (first_name, last_name, email, total_paid, attendee_type, room_number) VALUES 
('Alice', 'Zhu', 'alice@queensu.ca', 50.00, 'Student', 101),
('Bob', 'Miller', 'bob@queensu.ca', 50.00, 'Student', 101);

-- Professionals (paying 100.00)
INSERT INTO ATTENDEE (first_name, last_name, email, total_paid, attendee_type) VALUES 
('Charlie', 'Davis', 'charlie@pro.com', 100.00, 'Professional');

-- Sponsors (paying 0.00, linked to company)
INSERT INTO ATTENDEE (first_name, last_name, email, total_paid, attendee_type, company_name) VALUES 
('Diana', 'Prince', 'diana@bionic.com', 0.00, 'Sponsor', 'Bionic-Tech');