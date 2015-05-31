
using System.Windows;
using System.Windows.Controls;
using System.Windows.Input;
using MySql.Data.MySqlClient;

namespace Autoschool
{

    public partial class EditQuery : Window
    {
        public Query SelectedItem { get; set; }

        private readonly string _text;
        private readonly string _name;
        private readonly string _id;
        private readonly string _autoschool;

        public EditQuery(Query query)
        {
            _text = query.Text;
            _name = query.Name;
            _id = query.Id;
            _autoschool = query.Autoschool;
            SelectedItem = query;
            InitializeComponent();
        }

        private void BtnCancel_Click(object sender, RoutedEventArgs e)
        {
            TxtName.Text = _name;
            TxtText.Text = _text;
            TxtName.InvalidateVisual();
            TxtText.InvalidateVisual();
            TxtName.Focus();
            TxtText.Focus();
            Close();
        }

        private async void BtnSave_Click(object sender, RoutedEventArgs e)
        {
            try
            {
                TxtName.Focus();
                TxtText.Focus();
                TxtName.InvalidateVisual();
                TxtText.InvalidateVisual();
                using (var connection = new MySqlConnection(DatabaseModel.ConnectionString))
                {
                    connection.Open();
                    const string query =
                        @"UPDATE `cached_queries` SET query_name = @query_name, query_text = @query_text " +
                        "WHERE id = @id";
                    var command = connection.CreateCommand();
                    command.CommandText = query;
                    command.Parameters.AddWithValue("@query_name", SelectedItem.Name);
                    command.Parameters.AddWithValue("@query_text", SelectedItem.Text);
                    command.Parameters.AddWithValue("@id", SelectedItem.Id);
                    await command.ExecuteNonQueryAsync();
                }
                Close();
            }
            catch
            {
                MessageBox.Show(@"Что-то пошло не так. Данные не будут сохранены.");
                Close();
            }
        }

        private void EditQueryWindow_MouseDown(object sender, MouseButtonEventArgs e)
        {
            DragMove();
        }
    }
}
