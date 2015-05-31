using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using System.Windows;
using System.Windows.Controls;
using System.Windows.Data;
using System.Windows.Documents;
using System.Windows.Input;
using System.Windows.Media;
using System.Windows.Media.Imaging;
using System.Windows.Shapes;

namespace Autoschool
{

    public partial class EditQuery : Window
    {
        public Query SelectedItem { get; set; }

        public EditQuery(Query query)
        {
            SelectedItem = query;
            InitializeComponent();
        }

        private void BtnCancel_Click(object sender, RoutedEventArgs e)
        {
            Close();
        }

        private void BtnSave_Click(object sender, RoutedEventArgs e)
        {

        }

        private void EditQueryWindow_MouseDown(object sender, MouseButtonEventArgs e)
        {
            DragMove();
        }
    }
}
