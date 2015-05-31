﻿using System;
using System.Collections.Generic;
using System.Collections.ObjectModel;
using System.Data;
using System.Diagnostics;
using System.Linq;
using System.Windows;
using System.Windows.Controls;
using System.Windows.Documents;
using System.Windows.Input;
using System.Windows.Media;
using MySql.Data.MySqlClient;
using System.Drawing;
using System.Text;
using System.Threading.Tasks;
using System.Windows.Forms;
using DotLiquid;
using DotLiquid.Util;
using PdfSharp.Pdf;
using PdfSharp.Pdf.IO;
using Spire.Pdf;
using Spire.Pdf.Graphics;
using Button = System.Windows.Forms.Button;
using CheckBox = System.Windows.Forms.CheckBox;
using Control = System.Windows.Forms.Control;
using KeyEventArgs = System.Windows.Input.KeyEventArgs;
using Label = System.Windows.Forms.Label;
using MessageBox = System.Windows.MessageBox;
using PdfDocument = Spire.Pdf.PdfDocument;
using Size = System.Drawing.Size;
using TextBox = System.Windows.Forms.TextBox;

namespace Autoschool
{
    /// <summary>
    /// Main window of the program.
    /// </summary>
    public partial class Main : Window
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
                DataGrid.ItemsSource = null;
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
                    .Intersect(new[] {"INSERT", "UPDATE", "DROP", "DELETE", "TRUNCATE"})
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

        private void Button_Click_1(object sender, RoutedEventArgs e)
        {
            var pdf = new PdfDocument();
            var page = pdf.Pages.Add();

            //var lessons = DatabaseModel.GetLessonsString();
            //var encoding = Encoding.UTF8;
            //var print = encoding.GetString(Encoding.UTF8.GetBytes(lessons));

            var utf8Str = "\t\t\tja;dlf hjsdf";
            var bytes = Encoding.Default.GetBytes(utf8Str);
            utf8Str = Encoding.UTF8.GetString(bytes);


            var font = new PdfFont(PdfFontFamily.Helvetica, 12f, PdfFontStyle.Regular);
            page.Canvas.DrawString(utf8Str, font, PdfBrushes.Green, new PointF(0, 20f),
                new PdfStringFormat(PdfTextAlignment.Center));
            pdf.SaveToFile(@"sample.pdf");
            Process.Start(@"sample.pdf");
        }

        private readonly ObservableCollection<string> _criteriaSearch = new ObservableCollection<string>();
        private DataTable Students;
        private DataTable Teachers;
        private DataTable Autoschools;
        private DataTable Lessons;


        private async void Window_Loaded(object sender, RoutedEventArgs e)
        {
            ProgressBar.IsIndeterminate = true;
            await Task.Run(() => GetSearchGridTable());
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
                " ORDER BY date.start_time;";



            using (var connection = new MySqlConnection(connString))
            {
                connection.Open();
                using (var cmd = new MySqlCommand(schools, connection))
                {
                    var dt = new DataTable();
                    var adapter = new MySqlDataAdapter(cmd);
                    adapter.Fill(dt);
                    Autoschools = dt;
                }
                using (var cmd = new MySqlCommand(teachers, connection))
                {
                    var dt = new DataTable();
                    var adapter = new MySqlDataAdapter(cmd);
                    adapter.Fill(dt);
                    Teachers = dt;
                }
                using (var cmd = new MySqlCommand(students, connection))
                {
                    var dt = new DataTable();
                    var adapter = new MySqlDataAdapter(cmd);
                    adapter.Fill(dt);
                    Students = dt;
                }
                using (var cmd = new MySqlCommand(lessons, connection))
                {
                    var dt = new DataTable();
                    var adapter = new MySqlDataAdapter(cmd);
                    adapter.Fill(dt);
                    Lessons = dt;
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
                        SearchGrid.DataContext = Autoschools;
                        break;
                    case "Преподаватели":
                        SearchGrid.DataContext = Teachers;
                        break;
                    case "Студенты":
                        SearchGrid.DataContext = Students;
                        break;
                    case "Занятия":
                        SearchGrid.DataContext = Lessons;
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
            public string Word;
        }

        internal void CheckWordsInRun(Run run)
        {
            var sIndex = 0;

            var text = Query;

            for (var i = 0; i < text.Length; ++i)
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
    }
}
