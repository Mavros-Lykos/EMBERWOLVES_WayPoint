# 🚀 How to Run Waypoint Dispatch Locally

I noticed you are currently running `php -S Localhost:8000` in your terminal. **You should stop this command.** 

Here is why:
1. Laravel must be served from the `public` folder, not the root folder.
2. Our `.env` is configured to connect to PostgreSQL and Redis inside Docker. Your local PHP installation won't be able to connect to the database without Docker.
3. The Tailwind v4 CSS won't compile without Vite running.

To properly start the entire stack (Database, Cache, Web Server, and CSS Engine), follow these steps:

### Step 1: Start the Backend (Docker)
Open a new terminal window in your project root (`d:\Projects\rootcode`) and run:
```powershell
docker compose up -d
```
*This command boots up the PHP application, the PostgreSQL database (already seeded with our hackathon dummy data), and the Redis cache.*

### Step 2: Start the Frontend (Vite)
Open a second, separate terminal window in your project root and run:
```powershell
npm run dev
```
*This starts the Vite development server. It compiles our Tailwind CSS and hot-reloads the browser if we make any UI changes.*

### Step 3: Access the Application
Once both commands are running, open your web browser and go to:
[http://localhost:8000/login](http://localhost:8000/login)

You can log in using the "Magic Links" at the bottom of the login page to easily switch between the Driver, Dispatcher, Loader, and Store Manager personas!
