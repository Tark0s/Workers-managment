# Employee Management API

## Description
This API application enables efficient employee management through a set of RESTful endpoints. The main features include:
- Adding new users to the database.
- Logging working hours for employees.
- Generating summaries based on recorded work hours.

The application is designed to be simple, scalable, and easy to integrate with existing systems.

## Features
- **User Management**: Add and manage employee information in the database.
- **Work Hour Tracking**: Submit and store working hours for individual employees.
- **Reporting**: Generate detailed summaries of employees’ total work hours.

## Usage
The API provides endpoints for:
1. **Adding Users**: Create new employee records in the system.
2. **Recording Work Hours**: Log daily work hours for employees.
3. **Generating Summaries**: Retrieve aggregated work hour data for reporting purposes.

## Example Use Cases
- **HR Departments**: Track employee attendance and working hours for payroll processing.
- **Managers**: Monitor team productivity by reviewing recorded hours.
- **Employees**: View individual work hour summaries.

## API Endpoints Overview
- **POST `/api/workers`**: Add a new user.
- **POST `/api/work-times`**: Record work hours for a specific employee.
- **GET `/api/work-times`**: Generate a summary of employees' work hours.
