using System;
using System.Collections.Generic;
using System.Collections.ObjectModel;
using System.Data;
using System.Linq;
using System.Windows;
using System.Windows.Controls;
using System.Windows.Documents;
using System.Windows.Input;
using System.Windows.Media;
using MySql.Data.MySqlClient;
using System.Threading.Tasks;
using System.Windows.Forms;
using Button = System.Windows.Forms.Button;
using CheckBox = System.Windows.Forms.CheckBox;
using Control = System.Windows.Forms.Control;
using KeyEventArgs = System.Windows.Input.KeyEventArgs;
using Label = System.Windows.Forms.Label;
using MessageBox = System.Windows.MessageBox;
using Size = System.Drawing.Size;
using TextBox = System.Windows.Forms.TextBox;

namespace Autoschool
{
    /// <summary>
    /// Main window of the program.
    /// </summary>
    public partial class Main
    {
        private readonly User _currentUser;
        private readonly string _currentAutoschool;
        private readonly bool _isAdmin;
        public Main(User user, string autoschool)
        {
            InitializeComponent();
            _currentUser = user;
            _currentAutoschool = autoschool;
            _isAdmin = _currentUser.Role.Equals("administrator");
            if (!_isAdmin)
            {
                Statistics.Visibility = Visibility.Hidden;
            }
        }

        private void TriggerQueryEvent(object sender, QueryEventArgs e)
        {
            InputQuery.Document.Blocks.Clear();
            InputQuery.Document.Blocks.Add(new Paragraph(new Run(e.Message)));
            using (var connection = new MySqlConnection(DatabaseModel.ConnectionString))
            {
                connection.Open();
                using (var cmd = new MySqlCommand(e.Message, connection))
                {
                    var dt = new DataTable();
                    var adapter = new MySqlDataAdapter(cmd);
                    adapter.Fill(dt);
                    DataGrid.DataContext = dt;
                }
            }
        }

        private void Window_MouseDown(object sender, MouseButtonEventArgs e)
        {
            DragMove();
        }

        private void ButtonWindowClose(object sender, RoutedEventArgs e)
        {
            new MainWindow().Show();
            Close();
        }

        private void ButtonWindowMinimize(object sender, RoutedEventArgs e)
        {
            WindowState = WindowState.Minimized;
        }

        private async void btnReload_Click(object sender, RoutedEventArgs e)
        {
            try
            {
                ProgressBar.IsIndeterminate = true;
                await Task.Run(() => DatabaseModel.SeedDatabase());
                await Task.Run(() => GetSearchGridTable());
                await Task.Run(() => FillStatisticsList());

                ProgressBar.IsIndeterminate = false;

            }
            catch (Exception ex)
            {
                MessageBox.Show(ex.Message + "\n" + ex.StackTrace);
            }
        }

        private void InputQuery_PreviewKeyDown(object sender, KeyEventArgs e)
        {

            if (e.Key != Key.Enter) return;
            if ((!Keyboard.IsKeyDown(Key.RightCtrl) && !Keyboard.IsKeyDown(Key.LeftCtrl)) ||
                !Keyboard.IsKeyDown(Key.Enter)) return;
            Button_Click(sender, e);
            e.Handled = true;
        }

        private void Button_Click(object sender, RoutedEventArgs e)
        {
            try
            {
                FillGrid();
            }
            catch (MySqlException ex)
            {
                //DataGrid.ItemsSource = null;
                DataGrid.Items.Refresh();
                MessageBox.Show(ex.Message);
            }
            catch(Exception ex)
            {
                //DataGrid.ItemsSource = null;
                DataGrid.Items.Refresh();
                MessageBox.Show(ex.Message + "\n" + ex.StackTrace);
            }
        }

        private void FillGrid()
        {
            try
            {
                var connString = DatabaseModel.ConnectionString;

                var sql = Query;
                if (sql.Split(' ')
                    .Select(x => x.ToUpper())
                    .Intersect(new[] {"INSERT", "UPDATE", "DROP", "DELETE", "TRUNCATE", "ALTER"})
                    .Any())
                {
                    throw new InvalidOperationException("Данная команда запрещена!");
                }

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
                }
            }
            catch (Exception ex)
            {
                MessageBox.Show(ex.Message);
            }
        }

        private string Query
        {
            get { return new TextRange(InputQuery.Document.ContentStart, InputQuery.Document.ContentEnd).Text; }
        }

        /// <summary>
        /// Exports the data about teachers to PDF
        /// </summary>
        /// <param name="sender"></param>
        /// <param name="e"></param>
        private async void Button_Click_1(object sender, RoutedEventArgs e)
        {
            try
            {
                if (!_isAdmin)
                {
                    ProgressBar.IsIndeterminate = true;
                    MessageBox.Show("Ваш отчёт откроется как только будет готов. ");
                    await ExportToPdfAsync("D://teachers.pdf", true);
                    ProgressBar.IsIndeterminate = false;
                }
                else
                {
                    MessageBox.Show(
                        "Администратор не может создавать отчёты так как он не принадлежит ни одной автошколе");
                }
            }
            catch (Exception ex)
            {
                MessageBox.Show(ex.Message + "\n" + ex.StackTrace);
            }
        }

        private async void Button_Click_4(object sender, RoutedEventArgs e)
        {
            try
            {
                if (!_isAdmin)
                {
                    ProgressBar.IsIndeterminate = true;
                    MessageBox.Show("Ваш отчёт откроется как только будет готов. ");
                    await ExportToPdfAsync("D://students.pdf", false);
                    ProgressBar.IsIndeterminate = false;
                }
                else
                {
                    MessageBox.Show(
                        "Администратор не может создавать отчёты так как он не принадлежит ни одной автошколе");
                }
            }
            catch (Exception ex)
            {
                MessageBox.Show(ex.Message + "\n" + ex.StackTrace);
            }
        }

        private readonly ObservableCollection<string> _criteriaSearch = new ObservableCollection<string>();
        private DataTable _students;
        private DataTable _teachers;
        private DataTable _autoschools;
        private DataTable _lessons;


        private async void Window_Loaded(object sender, RoutedEventArgs e)
        {
            ProgressBar.IsIndeterminate = true;
            await Task.Run(() => GetSearchGridTable());
            if (_isAdmin)
            {
                await Task.Run(() => FillStatisticsList());
            }
            ProgressBar.IsIndeterminate = false;

            if (_isAdmin)
            {
                _criteriaSearch.Add("Автошколы");
                _criteriaSearch.Add("Студенты");
                _criteriaSearch.Add("Преподаватели");
                _criteriaSearch.Add("Занятия");
            }
            else
            {
                _criteriaSearch.Add("Студенты");
                _criteriaSearch.Add("Преподаватели");
                _criteriaSearch.Add("Занятия");
            }
            CriteriaSearch.FontSize = 16d;
            CriteriaSearch.VerticalContentAlignment = VerticalAlignment.Center;
            CriteriaSearch.ItemsSource = _criteriaSearch;

            TxtDescription.Content += " Для просмотра\nопределённых статистических данных выберите в меню\n" +
                                      "слева один из элементов списка. Добавить " +
                                      "статистическую\nвыборку можно на панели SQL редактора,\n при сохранении " +
                                      "запроса отметив, что он относится к статистике.";
        }

        public ObservableCollection<Query> StatisticsQueries
        {
            get { return FillStatisticsList(); }
        }

        private static ObservableCollection<Query> FillStatisticsList()
        {
            const string statsQuery = "SELECT * FROM cached_queries WHERE is_statistics IS TRUE;";
            var connection = new MySqlConnection(DatabaseModel.ConnectionString);
            connection.Open();

            var cmd = new MySqlCommand(statsQuery, connection);

            cmd.ExecuteNonQuery();
            var reader = cmd.ExecuteReader();
            cmd.CommandType = CommandType.Text;

            var stats = new ObservableCollection<Query>();

            while (reader.Read())
            {
                stats.Add(new Query
                {
                    Id = reader["id"].ToString(),
                    Autoschool = reader["autoschool"].ToString(),
                    IsStatistics = reader["is_statistics"].ToString(),
                    Name = reader["query_name"].ToString(),
                    Text = reader["query_text"].ToString()
                });
            }
            connection.Close();
            return stats;
        }

        public void FillStatisticsGrid(object sender, MouseButtonEventArgs e)
        {
            if (LstStat.SelectedIndex < 0) return;
            var currentItem = LstStat.SelectedItem as dynamic;
            using (var connection = new MySqlConnection(DatabaseModel.ConnectionString))
            {
                connection.Open();
                using (var cmd = new MySqlCommand(currentItem.Text, connection))
                {
                    var dt = new DataTable();
                    var adapter = new MySqlDataAdapter(cmd);
                    adapter.Fill(dt);
                    GridStatistics.DataContext = dt;
                }
                connection.Close();
            }
        }

        private void GetSearchGridTable()
        {
            var connString = DatabaseModel.ConnectionString;

            const string schools = "SELECT id, name, contacts FROM autoschool;";

            var teachers =
                "SELECT `teacher`.id, `teacher`.name, `teacher`.type, `teacher`.email, `teacher`.phone_number, `autoschool`.name AS 'autoschool' " +
                "FROM `autoschool`, `teacher` WHERE `autoschool`.id = `teacher`.autoschool_id" +
                (_isAdmin ? ";" : (" AND `autoschool`.name = '" + _currentAutoschool + "';"));

            var students =
                "SELECT `student`.id, `student`.name, `student`.email, `group`.name AS 'group', `autoschool`.name AS 'autoschool' " +
                "FROM `student`, `group`, `autoschool` WHERE `student`.group_id = `group`.id AND " +
                "`group`.autoschool_id = `autoschool`.id" +
                (_isAdmin ? ";" : (" AND `autoschool`.name = '" + _currentAutoschool + "';"));

            var lessons =
                "SELECT DISTINCT teacher.name AS 'teacher', autoschool.name AS 'autoschool', lesson.room, lesson.meet_point, lesson.is_reserved, date.day, date.start_time, " +
                "date.finish_time FROM lesson, teacher, date, autoschool " +
                "WHERE date.id = lesson.date_id AND autoschool.id = teacher.autoschool_id " +
                (_isAdmin ? string.Empty : (" AND autoschool.name = '" + _currentAutoschool + "'")) +
                " GROUP BY teacher.name, autoschool.name, lesson.room, lesson.meet_point, lesson.is_reserved, date.day, date.start_time, date.finish_time ORDER BY date.start_time;";



            using (var connection = new MySqlConnection(connString))
            {
                connection.Open();
                using (var cmd = new MySqlCommand(schools, connection))
                {
                    var dt = new DataTable();
                    var adapter = new MySqlDataAdapter(cmd);
                    adapter.Fill(dt);
                    _autoschools = dt;
                }
                using (var cmd = new MySqlCommand(teachers, connection))
                {
                    var dt = new DataTable();
                    var adapter = new MySqlDataAdapter(cmd);
                    adapter.Fill(dt);
                    _teachers = dt;
                }
                using (var cmd = new MySqlCommand(students, connection))
                {
                    var dt = new DataTable();
                    var adapter = new MySqlDataAdapter(cmd);
                    adapter.Fill(dt);
                    _students = dt;
                }
                using (var cmd = new MySqlCommand(lessons, connection))
                {
                    var dt = new DataTable();
                    var adapter = new MySqlDataAdapter(cmd);
                    adapter.Fill(dt);
                    _lessons = dt;
                }

                connection.Close();
            }
        }

        private void CriteriaSearch_SelectionChanged(object sender, SelectionChangedEventArgs e)
        {
            try
            {
                switch (CriteriaSearch.SelectedValue.ToString())
                {
                    case "Автошколы":
                        SearchGrid.DataContext = _autoschools;
                        break;
                    case "Преподаватели":
                        SearchGrid.DataContext = _teachers;
                        break;
                    case "Студенты":
                        SearchGrid.DataContext = _students;
                        break;
                    case "Занятия":
                        SearchGrid.DataContext = _lessons;
                        break;
                    default:
                        return;
                }
            }
            catch (Exception ex)
            {
                MessageBox.Show(ex.Message + "\n" + ex.StackTrace);
            }
        }

        private void Button_Click_2(object sender, RoutedEventArgs e)
        {
            new DbStructure().Show();
        }

        public async Task<ObservableCollection<Query>> GetQueries(string autoschool)
        {
            return await Task.Run(() => DatabaseModel.GetCachedQueriesList(autoschool, _isAdmin));
        }




        // ===================== SYNTAX HIGHLIGHTING CODE ================== //

        private readonly List<Tag> _mTags = new List<Tag>();

        private readonly List<char> _specials = new List<char> {'\n', '\r', '\t', ';', '(', ')'};

        private readonly List<string> _tags = new List<string>
        {
            "SELECT",
            "FROM",
            "WHERE",
            "JOIN",
            "DESC",
            "ASC",
            "ORDER",
            "AND",
            "OR",
            "INNER",
            "OUTER",
            "NATURAL",
            "NULL",
            "ON",
            "HAVING",
            "GROUP",
            "BY",
            "CREATE",
            "VIEW",
            "AS",
            "COUNT",
            "MIN",
            "MAX",
            "AVG",
            "GRANT",
            "IN",
            "IS",
            "NULL",
            "SHOW",
            "TABLE",
            "TABLES",
            "PRINT",
            "DISTINCT",
            "LEFT",
            "RIGHT",
            "LIKE",
            "CASE",
            "WHEN",
            "IF",
            "ELSE",
            "COUNT",
            "END",
            "THEN"
        };

        new struct Tag
        {
            public TextPointer StartPosition;
            public TextPointer EndPosition;
#pragma warning disable 414
            public string Word;
#pragma warning restore 414
        }

        internal void CheckWordsInRun(Run run)
        {
            var sIndex = 0;

            var text = Query;

            for (var i = 0; i < text.Length; ++i)
            {
                if (!(Char.IsWhiteSpace(text[i]) | GetSpecials(text[i]))) continue;
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
                    var text = ((Run)navigator.Parent).Text;
                    if (text != string.Empty)
                    {
                        CheckWordsInRun((Run)navigator.Parent);
                    }
                }
                navigator = navigator.GetNextContextPosition(LogicalDirection.Forward);
            }

            for (var i = 0; i < _mTags.Count; ++i)
            {
                try
                {
                    var range = new TextRange(_mTags[i].StartPosition, _mTags[i].EndPosition);
                    range.Text = range.Text.ToUpper();
                    range.ApplyPropertyValue(TextElement.ForegroundProperty, new SolidColorBrush(Colors.DodgerBlue));
                    range.ApplyPropertyValue(TextElement.FontWeightProperty, FontWeights.Bold);
                    if (InputQuery.CaretPosition != null)
                    {
                        InputQuery.CaretPosition = InputQuery.CaretPosition.GetPositionAtOffset(0,
                            LogicalDirection.Forward);
                    }
                }
                catch
                {
                    // ignored
                }
            }

            InputQuery.TextChanged += InputQuery_TextChanged;
        }

        private async void BtnQueries_Click(object sender, RoutedEventArgs e)
        {
            var queriesWin = new QueriesWindow(await GetQueries(_currentAutoschool));
            queriesWin.RaiseQueryEvent += TriggerQueryEvent;
            queriesWin.ShowDialog();
            
        }

        private void Button_Click_3(object sender, RoutedEventArgs e)
        {
            try
            {
                var value = "...";
                bool isChecked;
                var queryText = new TextRange(InputQuery.Document.ContentStart, InputQuery.Document.ContentEnd).Text;
                if (InputBox("Сохранить запрос", "Имя запроса: ", ref value, out isChecked, _isAdmin) != System.Windows.Forms.DialogResult.OK)
                    return;
                var connection = new MySqlConnection(DatabaseModel.ConnectionString);
                connection.Open();
                if (_isAdmin)
                {
                    const string query =
                        "INSERT INTO `cached_queries` (query_name, query_text, is_statistics) VALUES (@query_name, @query_text, @is_stat);";
                    var command = connection.CreateCommand();
                    command.CommandText = query;
                    command.Parameters.AddWithValue("@query_name", value);
                    command.Parameters.AddWithValue("@query_text", queryText);
                    command.Parameters.AddWithValue("@query_text", isChecked);
                    command.ExecuteNonQuery();
                    command.Dispose();
                }
                else
                {
                    const string query =
                        "INSERT INTO `cached_queries` (query_name, query_text, autoschool) VALUES (@query_name, @query_text, @autoschool);";
                    var command = connection.CreateCommand();
                    command.CommandText = query;
                    command.Parameters.AddWithValue("@query_name", value);
                    command.Parameters.AddWithValue("@query_text", queryText);
                    command.Parameters.AddWithValue("@autoschool", _currentAutoschool);
                    command.ExecuteNonQuery();
                    command.Dispose();
                }
                connection.Close();
            }
            catch (Exception ex)
            {
                MessageBox.Show(ex.Message + "\n" + ex.StackTrace);
            }
        }


        public static DialogResult InputBox(string title, string promptText, ref string value, out bool isChecked, bool isAdmin)
        {
            var form = new Form();
            var label = new Label();
            var textBox = new TextBox();
            var btnOk = new Button();
            var btnCancel = new Button();
            var checkBox = new CheckBox();

            form.Text = title;
            label.Text = promptText;
            textBox.Text = value;
            checkBox.Checked = false;

            checkBox.Text = @"Статистика";
            btnOk.Text = @"Сохранить";
            btnCancel.Text = @"Отмена";
            btnOk.DialogResult = System.Windows.Forms.DialogResult.OK;
            btnCancel.DialogResult = System.Windows.Forms.DialogResult.Cancel;

            
            var height = isAdmin ? 117 : 97;
            var buttonsY = isAdmin ? 82 : 62;
            checkBox.Visible = isAdmin;

            label.SetBounds(9, 20, 372, 13);
            textBox.SetBounds(12, 36, 372, 20);
            checkBox.SetBounds(12, 62, 372, 13);
            btnOk.SetBounds(228,buttonsY,75,23);
            btnCancel.SetBounds(309, buttonsY, 75, 23);

            label.AutoSize = true;
            checkBox.Anchor = checkBox.Anchor | AnchorStyles.Right;
            textBox.Anchor = textBox.Anchor | AnchorStyles.Right;
            btnOk.Anchor = AnchorStyles.Bottom | AnchorStyles.Right;
            btnCancel.Anchor = AnchorStyles.Bottom | AnchorStyles.Right;

            form.ClientSize = new Size(396, height);
            form.Controls.AddRange(new Control[] {label, textBox, checkBox, btnOk, btnCancel});
            form.ClientSize = new Size(Math.Max(300, label.Right + 10), form.ClientSize.Height);
            form.FormBorderStyle = FormBorderStyle.FixedDialog;
            form.StartPosition = FormStartPosition.CenterScreen;
            form.MinimizeBox = false;
            form.MaximizeBox = false;
            form.AcceptButton = btnOk;
            form.CancelButton = btnCancel;

            var dialogResult = form.ShowDialog();
            value = textBox.Text;
            isChecked = checkBox.Checked;
            return dialogResult;
        }

        private void LstStat_PreviewMouseRightButtonDown(object sender, MouseButtonEventArgs e)
        {
            if (LstStat.SelectedItem == null || LstStat.SelectedIndex < 0)
            {
                e.Handled = true;
            }
        }

        private void LstStat_PreviewMouseRightButtonUp(object sender, MouseButtonEventArgs e)
        {
            if (LstStat.SelectedItem == null || LstStat.SelectedIndex < 0)
            {
                e.Handled = true;
            }
        }

        private void Ui_PreviewMouseRightButtonDown(object sender, MouseButtonEventArgs e)
        {
            //e.Handled = true;
        }

        private void Ui_PreviewMouseRightButtonUp(object sender, MouseButtonEventArgs e)
        {
            //e.Handled = true;
        }

        public DataTable StatisticsDataTable { get; private set; }

        private void LstStat_MouseDoubleClick(object sender, MouseButtonEventArgs e)
        {
            if (LstStat.SelectedIndex < 0) return;
            var item = LstStat.SelectedItem as Query;
            using (var connection = new MySqlConnection(DatabaseModel.ConnectionString))
            {
                connection.Open();
                if (item != null)
                    using (var cmd = new MySqlCommand(item.Text, connection))
                    {
                        var dt = new DataTable();
                        var adapter = new MySqlDataAdapter(cmd);
                        adapter.Fill(dt);
                        StatisticsDataTable = dt;
                        GridStatistics.DataContext = StatisticsDataTable;
                    }
                connection.Close();
            }
            e.Handled = true;
        }

        private void MenuItem_OnClick(object sender, RoutedEventArgs e)
        {
            if (LstStat.SelectedIndex >= 0)
            {
                var item = LstStat.SelectedItem as dynamic;
                MessageBox.Show(item.Text);
                e.Handled = true;
            }
            e.Handled = true;
        }
    }
}
