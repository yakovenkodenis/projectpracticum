using System;
using System.Collections.ObjectModel;
using System.Windows;

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
            dynamic query = LstQueries.SelectedItem as dynamic;
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
    }
}
