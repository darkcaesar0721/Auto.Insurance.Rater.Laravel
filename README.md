# Auto Insurance Rater

This is a sample auto insurance rating application built with Laravel. 

## Overview

The application allows users to get a quote for auto insurance based on parameters like:

- Vehicle year, make, model
- Driver age
- Number of accidents
- Number of traffic violations

It calculates a risk score and premium estimate based on this data.

## Features

- User registration and login
- Form to enter quote parameters 
- Risk score calculation
- Premium estimate calculation
- Quote history and management for users
- Admin dashboard for managing quote plans and rates

## Installation

- Clone the repo
- Run `composer install`
- Create `.env` file and add your environment variables
- Run migrations `php artisan migrate`
- Import vehicle data `php artisan db:seed` 
- Install frontend dependencies `npm install`
- Build assets `npm run dev`
- Serve application `php artisan serve`

## Usage

- Register a new user account
- Login 
- Use the quote form to get a risk assessment and premium estimate
- View your quote history and details 

## Built With

- [Laravel](https://laravel.com/) - The PHP framework used
- [Bootstrap](https://getbootstrap.com/) - Frontend styling
- [Vue](https://vuejs.org/) - Frontend JavaScript framework

## License

This project is licensed under the MIT License - see the [LICENSE.md](LICENSE.md) file for details.
