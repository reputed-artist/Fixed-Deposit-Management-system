# Fixed-Deposit-Management-System 💰🏦

A comprehensive PHP-based Fixed Deposit Management System that allows users to manage and calculate fixed deposits with different banks such as SBI, Saraswat Bank, Yes Bank, and BOI.

## Features
- 📊 Manage multiple fixed deposits
- 💸 Calculate interest for different banks
- 📅 Display maturity dates
- ⏰ Alert for FD maturity
- 🔒 User authentication

## Getting Started

### Prerequisites
- [XAMPP](https://www.apachefriends.org/index.html) or any other local server setup.

### Installation

1. **Download the repository:**
    - Clone the repository or download the ZIP file.
      ```sh
      git clone https://github.com/reputed-artist/Fixed-Deposit-Management-system.git
      ```

2. **Copy the folder:**
    - Move the folder into your XAMPP's `htdocs` directory.
      ```sh
      cp -r Fixed-Deposit-Management-System /path/to/xampp/htdocs/
      ```

3. **Set Up the Database:**
    - Open your web browser and navigate to `http://localhost/phpmyadmin`.
    - Create a new database named `fd_mgmt`.
    - Import the `fd_mgmt.sql` file to create the necessary tables and data:
      - Click on the `fd_mgmt` database.
      - Go to the `Import` tab.
      - Choose the `fd_mgmt.sql` file from the project directory and click `Go`.

4. **Start your local server:**
    - Ensure Apache and MySQL are running in your XAMPP control panel.

5. **Access the application:**
    - Open your web browser and navigate to:
      ```
      http://localhost/Fixed-Deposit-Management-System
      ```

### Usage

1. **Login:**
    - Enter the login ID and password provided below:
      ```
      Username: admin@gmail.com
      Password: admin@123
      ```

2. **Manage Fixed Deposits:**
    - Add new fixed deposits, specify bank details, principal amount, interest rate, and duration.

3. **Calculate Interest:**
    - The system will automatically calculate the interest and maturity amount based on the bank's logic.

4. **Maturity Alerts:**
    - The system will check for FDs maturing today and show alerts with details including name, date, and FD ID.

### Supported Banks and Logic 🏦
- **SBI (State Bank of India)**
- **Saraswat Bank**
- **Yes Bank**
- **BOI (Bank of India)**

Each bank has its own logic for interest calculation which is handled by the system.

## Screenshots

![Screenshot 1](path/to/screenshot1.png)
![Screenshot 2](path/to/screenshot2.png)

## Contributing
Contributions are welcome! Please open an issue or submit a pull request.

## License
This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## Contact
For any queries, please contact [tejaschavda2020@gmail.com](mailto:tejaschavda2020@gmail.com).

---

Happy Managing! 💼
