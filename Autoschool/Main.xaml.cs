using System;
using System.Collections.Generic;
using System.Data;
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
using MySql.Data.MySqlClient;

namespace Autoschool
{
    /// <summary>
    /// Main window of the program.
    /// </summary>
    public partial class Main : Window
    {
        public Main()
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

        private void btnReload_Click(object sender, RoutedEventArgs e)
        {
            try
            {
                var sb = new StringBuilder();
                foreach (var x in WebsiteModel.GetGroup())
                {
                    sb.Append(x).Append(Environment.NewLine);
                }
                MessageBox.Show(sb.ToString());
            }
            catch (Exception ex)
            {
                MessageBox.Show(ex.Message + "\n" + ex.StackTrace);
            }
            //MessageBox.Show(DatabaseModel.GetData());
        }

        private void InputQuery_PreviewKeyDown(object sender, KeyEventArgs e)
        {
            if (e.Key == Key.Enter)
            {
                Button_Click(sender, e);
                e.Handled = true;
            }
        }

        private void Button_Click(object sender, RoutedEventArgs e)
        {
            try
            {
                FillGrid();
            }
            catch
            {
                DataGrid.ItemsSource = null;
                DataGrid.Items.Refresh();
                MessageBox.Show("Проверьте правильность запроса");
            }
        }

        private void FillGrid()
        {
            var connString = DatabaseModel.ConnectionString;

            var sql = Query;

            using (var connection = new MySqlConnection(connString))
            {
                connection.Open();
                using (var cmd = new MySqlCommand(sql, connection))
                {
                    var dt = new DataTable();
                    var adapter = new MySqlDataAdapter(cmd);
                    adapter.Fill(dt);
                    DataGrid.DataContext = dt;
                }
                connection.Close();
            }
        }

        private string Query
        {
            get { return new TextRange(InputQuery.Document.ContentStart, InputQuery.Document.ContentEnd).Text; }
        }


        // ===================== SYNTAX HIGHLIGHTING CODE ================== //

        private readonly List<Tag> _mTags = new List<Tag>();

        private readonly List<char> _specials = new List<char> {'\n', '\r', '\t'};
        private readonly List<string> _tags = new List<string> {"SELECT", "FROM", "WHERE", "JOIN", "DESC", "ASC", "ORDER BY"};

        new struct Tag
        {
            public TextPointer StartPosition;
            public TextPointer EndPosition;
            public string Word;
        }

        internal void CheckWordsInRun(Run run)
        {
            var sIndex = 0;

            var text = Query;

            for (int i = 0; i < text.Length; ++i)
            {
                if (Char.IsWhiteSpace(text[i]) | GetSpecials(text[i]))
                {
                    if (i > 0 && !(Char.IsWhiteSpace(text[i - 1]) | GetSpecials(text[i - 1])))
                    {
                        var eIndex = i - 1;
                        var word = text.Substring(sIndex, eIndex - sIndex + 1);
                        if (IsKnownTag(word))
                        {
                            var t = new Tag
                            {
                                StartPosition = run.ContentStart.GetPositionAtOffset(sIndex, LogicalDirection.Forward),
                                EndPosition =
                                    run.ContentStart.GetPositionAtOffset(eIndex + 1, LogicalDirection.Backward),
                                Word = word
                            };
                            _mTags.Add(t);
                        }
                    }
                    sIndex = i + 1;
                }
            }

            var lastWord = text.Substring(sIndex, text.Length - sIndex);
            if (IsKnownTag(lastWord))
            {
                var t = new Tag
                {
                    StartPosition = run.ContentStart.GetPositionAtOffset(sIndex, LogicalDirection.Forward),
                    EndPosition =
                        run.ContentStart.GetPositionAtOffset(text.Length, LogicalDirection.Backward),
                    Word = lastWord
                };
                _mTags.Add(t);
            }
        }

        private bool IsKnownTag(string tag)
        {
            return _tags.Exists(s => s.ToLower().Equals(tag.ToLower()));
        }

        private bool GetSpecials(char i)
        {
            return _specials.Any(item => item.Equals(i));
        }

        private void InputQuery_TextChanged(object sender, TextChangedEventArgs e)
        {
            if (InputQuery.Document == null)
            {
                return;
            }
            InputQuery.TextChanged -= InputQuery_TextChanged;

            _mTags.Clear();

            var documentRange = new TextRange(InputQuery.Document.ContentStart, InputQuery.Document.ContentEnd);
            documentRange.ClearAllProperties();

            var navigator = InputQuery.Document.ContentStart;
            while (navigator != null && navigator.CompareTo(InputQuery.Document.ContentEnd) < 0)
            {
                var context = navigator.GetPointerContext(LogicalDirection.Backward);
                if (context == TextPointerContext.ElementStart && navigator.Parent is Run)
                {
                    var text = ((Run) navigator.Parent).Text;
                    if (text != string.Empty)
                    {
                        CheckWordsInRun((Run) navigator.Parent);
                    }
                }
                navigator = navigator.GetNextContextPosition(LogicalDirection.Forward);
            }

            for (int i = 0; i < _mTags.Count; ++i)
            {
                try
                {
                    var range = new TextRange(_mTags[i].StartPosition, _mTags[i].EndPosition);
                    range.ApplyPropertyValue(TextElement.ForegroundProperty, new SolidColorBrush(Colors.DodgerBlue));
                    range.ApplyPropertyValue(TextElement.FontWeightProperty, FontWeights.Bold);
                }
                catch
                {
                    // ignored
                }
            }

            InputQuery.TextChanged += InputQuery_TextChanged;
        }
    }
}
