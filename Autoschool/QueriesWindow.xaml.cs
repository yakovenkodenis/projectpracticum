using System;
using System.Collections.ObjectModel;
using System.Windows;
using System.Windows.Controls;
using MySql.Data.MySqlClient;

namespace Autoschool
{
    public partial class QueriesWindow
    {
        public event EventHandler<QueryEventArgs> RaiseQueryEvent;

        public QueriesWindow(ObservableCollection<Query> queries)
        {
            Queries = queries;
            InitializeComponent();
        }

        public ObservableCollection<Query> Queries { get; protected set; }


        private void LstQueries_MouseDoubleClick(object sender, System.Windows.Input.MouseButtonEventArgs e)
        {
            if (LstQueries.SelectedIndex < 0) return;
            var query = LstQueries.SelectedItem as dynamic;
            if (RaiseQueryEvent != null) RaiseQueryEvent(this, new QueryEventArgs(query.Text));
        }

        private void BtnEdit_Click(object sender, RoutedEventArgs e)
        {
            try
            {
                new EditQuery(LstQueries.SelectedItem as Query).ShowDialog();
            }
            catch (Exception ex)
            {
                MessageBox.Show(ex.Message + "\n" + ex.StackTrace);
            }
        }

        private async void MenuItem_OnClick(object sender, RoutedEventArgs e)
        {
            try
            {
                using (var connection = new MySqlConnection(DatabaseModel.ConnectionString))
                {
                    connection.Open();
                    const string query =
                        @"DELETE FROM `cached_queries` WHERE id = @id;";
                    var command = connection.CreateCommand();
                    command.CommandText = query;
                    var item = LstQueries.SelectedItem as Query;
                    if (item != null)
                        command.Parameters.AddWithValue("@id", item.Id);
                    await command.ExecuteNonQueryAsync();
                }
                Queries.RemoveAt(LstQueries.SelectedIndex);
            }
            catch (Exception ex)
            {
                MessageBox.Show(ex.Message + "\n" + ex.StackTrace);
            }
        }
    }
}
