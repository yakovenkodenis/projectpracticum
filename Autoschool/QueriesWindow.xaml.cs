using System.Collections.ObjectModel;
using System.Linq;
using System.Text;
using System.Windows;

namespace Autoschool
{
    public partial class QueriesWindow
    {
        public QueriesWindow(ObservableCollection<Query> queries)
        {
            Queries = queries;
            InitializeComponent();
        }

        public ObservableCollection<Query> Queries { get; protected set; }
    }
}
