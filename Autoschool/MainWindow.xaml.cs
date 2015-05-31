using System;
using System.Linq;
using System.Security.Cryptography;
using System.Text;
using System.Windows;
using System.Windows.Input;

namespace Autoschool
{

    public partial class MainWindow : Window
    {
        public MainWindow()
        {
            InitializeComponent();
        }

        private void Window_MouseDown(object sender, MouseButtonEventArgs e)
        {
            DragMove();
        }

        private void ButtonWindowClose(object sender, RoutedEventArgs e)
        {
            Close();
        }

        private void ButtonWindowMinimize(object sender, RoutedEventArgs e)
        {
            WindowState = WindowState.Minimized;
        }

        private void ButtonOpenMain(object sender, RoutedEventArgs e)
        {
            try
            {
                if (Authenticate(txtLogin.Text, txtPassword.Password))
                {
                    try
                    {
                        _currentAutoschool = _currentUser.Role.Equals("moderator")
                            ? WebsiteModel.GetAutoschoolByUser(_currentUser)
                            : string.Empty;
                    }
                    catch
                    {
                        _currentAutoschool = string.Empty;
                    }
                    new Main(_currentUser, _currentAutoschool).Show();
                    Close();
                    if (_currentUser.Role.Equals("administrator"))
                    {
                        MessageBox.Show(string.Format("Здравствуйте, {0},{1}Вы вошли в систему как администратор.",
                            _currentUser.Name, Environment.NewLine));
                    }
                    else
                    {
                        MessageBox.Show(
                            string.Format("Здравствуйте, {0},{1}Вы вошли в систему как модератор автошколы {2}.",
                                _currentUser.Name, Environment.NewLine, _currentAutoschool));
                    }
                }
                else
                {
                    MessageBox.Show("Ошибка входа");
                }
            }
            catch (Exception ex)
            {
                MessageBox.Show(ex.Message + Environment.NewLine + ex.StackTrace);
            }
        }

        private static User _currentUser;
        private static string _currentAutoschool;
        private bool Authenticate(string login, string password)
        {
            _currentUser =
                WebsiteModel.GetUser()
                    .FirstOrDefault(user => (user.Role.Equals("administrator") || user.Role.Equals("moderator"))
                                            && user.Login.Equals(login));
            if (_currentUser == null)
            {
                txtPassword.Clear();
                txtLogin.Clear();
                MessageBox.Show("У Вас нет прав доступа");
                return false;
            }
            if (_currentUser.Password.Equals(Md5(password))) return true;
            txtPassword.Clear();
            txtLogin.Clear();
            if (_currentUser != null)
                MessageBox.Show(string.Format("Неверный пароль для пользователя {0}", _currentUser.Login));
            return false;
        }

        private static string Md5(string pass)
        {
            var encoded = new UTF8Encoding().GetBytes(pass);
            var hash = ((HashAlgorithm) CryptoConfig.CreateFromName("MD5")).ComputeHash(encoded);
            return BitConverter.ToString(hash).Replace("-", string.Empty).ToLower();
        }

    }
}
