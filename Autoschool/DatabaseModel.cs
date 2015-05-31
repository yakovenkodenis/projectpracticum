using System;
using System.Collections.Generic;
using System.Collections.ObjectModel;
using System.Data;
using System.Linq;
using System.Net;
using System.Text;
using System.Threading.Tasks;
using System.Windows;
using MySql.Data.MySqlClient;

namespace Autoschool
{
    public class DatabaseModel : Model
    {
        private static MySqlConnection _connection;
        private static List<Lesson> _lessons; 

        public new static string ConnectionString
        {
            get { return "SERVER=127.0.0.1;PORT=3306;DATABASE=autoschool;Uid=root;Pwd=;charset=utf8;"; }
        }

        private static readonly string[] Tables =
        {
            "`autoschool`", "`teacher`", "`group`", "`schedule`", "`schedule_student`", "`student`",
            "`date`", "`lesson`"
        };

        public static void SeedDatabase()
        {
            try
            {
                var internet = new InternetConnection();
                internet.Init();
                if (!internet.IsInternetConnected)
                {
                    throw new Exception("Проверьте соединение с интернетом");
                }
                if (!CheckConnection(ConnectionString))
                {
                    throw new Exception("Ошибка подключения");
                }

                _connection = new MySqlConnection(ConnectionString);
                _connection.Open();

                TruncateDatabase();
                SetDatabaseCharset();

                FillAutoschool();
                FillGroup();
                FillTeacher();
                FillDate();
                FillLesson();
                FillStudent();
                FillSchedule();
                FillScheduleStudent();

                _connection.Close();
            }
            catch (Exception e)
            {
                _connection.Close();
                MessageBox.Show("SEED DATABASE:\n" + e.Message + "\n" + e.StackTrace);
            }
        }

        public static void TruncateDatabase()
        {
            var query = new StringBuilder();
            foreach (var t in Tables)
            {
                query.Append("TRUNCATE ").Append(t).Append(";").Append(Environment.NewLine);
            }
            var command = _connection.CreateCommand();
            command.CommandText = query.ToString();
            command.ExecuteNonQuery();
            command.Dispose();
        }

        private static void SetDatabaseCharset()
        {
            const string query = "SET NAMES utf8;";
            var command = _connection.CreateCommand();
            command.CommandText = query;
            command.ExecuteNonQuery();
            command.Dispose();
        }

        private async static void FillAutoschool()
        {
            foreach (var school in WebsiteModel.GetAutoschool())
            {
                const string query = "INSERT INTO `autoschool` (id, name, contacts) VALUES (@id, @name, @contacts);";
                var command = _connection.CreateCommand();
                command.CommandText = query;
                command.Parameters.AddWithValue("@id", school.Id);
                command.Parameters.AddWithValue("@name", school.Name);
                command.Parameters.AddWithValue("@contacts", school.Contacts);
                await command.ExecuteNonQueryAsync();
            }
        }

        public static ObservableCollection<Query> GetCachedQueriesList()
        {
            var queries = new ObservableCollection<Query>();

            var connection = new MySqlConnection(ConnectionString);
            connection.Open();

            const string sql = "SELECT * FROM cached_queries;";

            var cmd = new MySqlCommand(sql, connection);

            var reader = cmd.ExecuteReader();
            cmd.CommandType = CommandType.Text;
            while (reader.Read())
            {
                queries.Add(new Query
                {
                    Id = reader["id"].ToString(),
                    Name = reader["query_name"].ToString(),
                    Text = reader["query_text"].ToString(),
                    Autoschool = reader["autoschool"].ToString()
                });
            }
            connection.Close();
            return queries;
        }

        private async static void FillTeacher()
        {
            var theoryTeachersIds = WebsiteModel.GetTheory().Select(t => t.TeacherId).ToList();
            foreach (var user in WebsiteModel.GetUser().Where(t => t.Role.Equals("teacher")))
            {
                const string query =
                    "INSERT INTO `teacher` (type, name, autoschool_id, id, birthday, phone_number, email) " +
                    "VALUES(@type, @name, @autoschool_id, @id, @birthday, @phone_number, @email);";
                var command = _connection.CreateCommand();
                command.CommandText = query;
                command.Parameters.AddWithValue("@type", theoryTeachersIds.Contains(user.Id) ? "theory" : "practice");
                command.Parameters.AddWithValue("@name", user.Name);
                command.Parameters.AddWithValue("@autoschool_id", user.AutoschoolId);
                command.Parameters.AddWithValue("@id", user.Id);
                command.Parameters.AddWithValue("@birthday", "NULL");
                command.Parameters.AddWithValue("@phone_number", user.Telephone);
                command.Parameters.AddWithValue("@email", user.Email);
                await command.ExecuteNonQueryAsync();
            }
        }

        private async static void FillGroup()
        {
            foreach (var group in WebsiteModel.GetGroup())
            {
                const string query = "INSERT INTO `group` (id, name, autoschool_id) VALUES (@id, @name, @autoschool_id);";
                var command = _connection.CreateCommand();
                command.CommandText = query;
                command.Parameters.AddWithValue("@id", group.GroupId);
                command.Parameters.AddWithValue("@name", group.Name);
                command.Parameters.AddWithValue("@autoschool_id", group.AutoschoolId);
                await command.ExecuteNonQueryAsync();
            }
        }

        private async static void FillDate()
        {
            try
            {
                GetLessons();
                foreach (var x in _lessons)
                {
                    const string query =
                        "INSERT INTO `date` (day, start_time, finish_time) VALUES(@day, @start_time, @finish_time);";
                    var command = _connection.CreateCommand();
                    command.CommandText = query;

                    var day = DateTime.Parse(x.StartTime.Split('-')[0]).ToString("yyyy-MM-dd");
                    var startTime = x.StartTime.Split('-')[0] + " " + x.StartTime.Split('-')[1];
                    var finishTime = x.FinishTime.Split('-')[0] + " " + x.FinishTime.Split('-')[1];
                    command.Parameters.AddWithValue("@day", day);
                    command.Parameters.AddWithValue("@start_time",
                        DateTime.Parse(startTime).ToString("yyyy-MM-dd HH:mm:ss"));
                    command.Parameters.AddWithValue("@finish_time",
                        DateTime.Parse(finishTime).ToString("yyyy-MM-dd HH:mm:ss"));
                    await command.ExecuteNonQueryAsync();
                }
            }
            catch (Exception e)
            {
                MessageBox.Show(e.Message + "\n" + e.Source + "\n" + e.StackTrace);
            }
        }

        public async static void FillStudent()
        {
            try
            {
                var groupIds = WebsiteModel.GetStudentToGroup();
                var studentsToFill =
                    WebsiteModel.GetUser().Where(user => user.Role.Equals("student")).Select(s => new Student
                    {
                        Name = s.Name,
                        Address = s.Address,
                        Birthday = "NULL",
                        Email = s.Email,
                        Id = s.Id,
                        Telephone = s.Telephone,
                        Password = s.Password,
                        GroupId = groupIds.Find(stud => stud.StudentId.Equals(s.Id)).GroupId
                    }).ToList();
                foreach (var s in studentsToFill)
                {
                    const string query =
                        "INSERT INTO `student` (id, phone_number, email, password, name, group_id, birthday)" +
                        "VALUES (@id, @phone_number, @email, @password, @name, @group_id, @birthday)";
                    var command = _connection.CreateCommand();
                    command.CommandText = query;
                    command.Parameters.AddWithValue("@id", s.Id);
                    command.Parameters.AddWithValue("@phone_number", s.Telephone);
                    command.Parameters.AddWithValue("@email", s.Email);
                    command.Parameters.AddWithValue("@password", s.Password);
                    command.Parameters.AddWithValue("@name", s.Name);
                    command.Parameters.AddWithValue("@group_id", s.GroupId);
                    command.Parameters.AddWithValue("@birthday", s.Birthday);

                    await command.ExecuteNonQueryAsync();
                }
            }
            catch (Exception e)
            {
                MessageBox.Show(e.Message + "\n" + e.StackTrace);
            }
        }

        public async static void FillLesson()
        {
            try
            {
                GetLessons();
                foreach (var x in _lessons)
                {
                    const string query =
                        "INSERT INTO `lesson` (room, is_reserved, meet_point, date_id) VALUES (@room, @is_reserved, @meet_point, @date_id);";
                    var command = _connection.CreateCommand();
                    command.CommandText = query;
                    command.Parameters.AddWithValue("@room", x.Room);
                    command.Parameters.AddWithValue("@is_reserved", !x.MeetPoint.Equals(string.Empty));
                    command.Parameters.AddWithValue("@meet_point", x.MeetPoint);

                    var startTime = x.StartTime.Split('-')[0] + " " + x.StartTime.Split('-')[1];
                    var finishTime = x.FinishTime.Split('-')[0] + " " + x.FinishTime.Split('-')[1];

                    var dateId = "-1";
                    foreach (
                        var d in GetDates().Where(d => d.StartTime.Equals(startTime) && d.FinishTime.Equals(finishTime))
                        )
                    {
                        dateId = d.Id;
                        break;
                    }
                    command.Parameters.AddWithValue("@date_id", dateId);

                    await command.ExecuteNonQueryAsync();
                }
            }
            catch (Exception e)
            {
                MessageBox.Show(e.Message + "\n" + e.StackTrace);
            }
        }

        public async static void FillSchedule()
        {
            GetLessons();
            var teachers = WebsiteModel.GetUser().Where(user => user.Role.Equals("teacher")).ToList();
            var schedule = GetLessonsList().Select(x => new Schedule
            {
                LessonId = x.LessonId,
                TeacherId = GetTeacherId(teachers, x)
            }).ToList();

            foreach (var s in schedule)
            {
                const string query = @"INSERT INTO `schedule` (lesson_id, teacher_id) " +
                                     "VALUES (@lesson_id, @teacher_id);";
                var command = _connection.CreateCommand();
                command.CommandText = query;
                command.Parameters.AddWithValue("@lesson_id", s.LessonId);
                command.Parameters.AddWithValue("@teacher_id", s.TeacherId);
                await command.ExecuteNonQueryAsync();
            }
        }

        public async static void FillScheduleStudent()
        {
            GetLessons();
            var students = WebsiteModel.GetUser().Where(user => user.Role.Equals("student")).ToList();
            var schedStud = GetLessonsList().Select(x => new ScheduleStudent
            {
                StudentId = GetStudentId(students, x),
                ScheduleId = GetScheduleId(x)
            });

            foreach (var x in schedStud)
            {
                const string query = "INSERT INTO `schedule_student` (student_id, schedule_id) " +
                                     "VALUES (@student_id, @schedule_id);";
                var command = _connection.CreateCommand();
                command.CommandText = query;
                command.Parameters.AddWithValue("@student_id", x.StudentId);
                command.Parameters.AddWithValue("@schedule_id", x.ScheduleId);
                await command.ExecuteNonQueryAsync();
            }
        }

        private static string GetScheduleId(Lesson x)
        {
            if (!CheckConnection(ConnectionString))
            {
                throw new Exception("Ошибка подключения");
            }

            var connection = new MySqlConnection(ConnectionString);
            connection.Open();
            GetLessons();
            const string sql = @"SELECT * FROM `schedule`;";

            var cmd = connection.CreateCommand();
            cmd.CommandType = CommandType.Text;
            cmd.CommandText = sql;
            var reader = cmd.ExecuteReader();
            var schedules = new List<Schedule>();
            while (reader.Read())
            {
                schedules.Add(new Schedule
                {
                    Id = reader["id"].ToString(),
                    LessonId = reader["lesson_id"].ToString(),
                    TeacherId = reader["teacher_id"].ToString()
                });
            }
            reader.Close();
            connection.Close();

            foreach (var s in schedules.Where(ss => ss.LessonId.Equals(x.LessonId)))
            {
                return s.Id;
            }
            return "NULL";
        }

        private static string GetTeacherId(IEnumerable<User> teachers, Lesson x)
        {
            foreach (var t in teachers.Where(t => t.Name.Equals(x.TeacherName)))
            {
                return t.Id;
            }
            return "NULL";
        }

        private static string GetStudentId(IEnumerable<User> students, Lesson x)
        {
            foreach (var t in students.Where(t => t.Name.Equals(x.StudentName)))
            {
                return t.Id;
            }
            return "NULL";
        }

        private static IEnumerable<Lesson> GetLessonsList()
        {
            if (!CheckConnection(ConnectionString))
            {
                throw new Exception("Ошибка подключения");
            }

            var connection = new MySqlConnection(ConnectionString);
            connection.Open();
            GetLessons();
            const string sql = @"SELECT * FROM `lesson`;";

            var cmd = connection.CreateCommand();
            cmd.CommandType = CommandType.Text;
            cmd.CommandText = sql;
            var reader = cmd.ExecuteReader();
            var result = new List<Lesson>();
            result.AddRange(_lessons);
            var i = 0;
            while (reader.Read() && i < result.Count)
            {
                result[i++].LessonId = reader["id"].ToString();
            }

            reader.Close();
            connection.Close();

            return result;
        } 

        private static IEnumerable<Date> GetDates()
        {
            if (!CheckConnection(ConnectionString))
            {
                throw new Exception("Ошибка подключения");
            }

            var connection = new MySqlConnection(ConnectionString);
            connection.Open();
            const string sql = @"SELECT * FROM `date`;";

            var cmd = connection.CreateCommand();
            cmd.CommandType = CommandType.Text;
            cmd.CommandText = sql;

            var reader = cmd.ExecuteReader();
            var result = new List<Date>();
            while (reader.Read())
            {
                result.Add(new Date
                {
                    Id = reader["id"].ToString(),
                    Day = reader["day"].ToString(),
                    StartTime = reader["start_time"].ToString(),
                    FinishTime = reader["finish_time"].ToString()
                });
            }

            reader.Close();
            connection.Close();

            return result;
        }

        public static string GetLessonsString()
        {
            GetLessons();
            var sb = new StringBuilder();
            foreach (var x in _lessons)
            {
                sb.Append(x);
            }
            return sb.ToString();
        }

        private static void GetLessons()
        {
            var theory = new StringBuilder();
            var practice = new StringBuilder();

            var students = WebsiteModel.GetUser().Where(x => x.Role.Equals("student")).ToList();

            using (var client = new WebClient())
            {
                client.Encoding = Encoding.UTF8;
                // ReSharper disable once AccessToDisposedClosure
                foreach (var response in students.Select(x => client.DownloadString(
                    string.Format(
                        @"http://www.autoschools.kh.ua/index.php/mobile/Theory/user/{0}/password/{1}/",
                        x.Login, x.Password))))
                {
                    theory.Append(response).Append(Environment.NewLine);
                }

                // ReSharper disable once AccessToDisposedClosure
                foreach (var response in students.Select(x => client.DownloadString(string.Format(
                    @"http://www.autoschools.kh.ua/index.php/mobile/practice/user/{0}/password/{1}/", x.Login,
                    x.Password))))
                {
                    practice.Append(response).Append(Environment.NewLine);
                }
            }

            theory = new StringBuilder(theory.ToString().Substring(0, theory.ToString().Length - 2));
            practice = new StringBuilder(practice.ToString().Substring(0, practice.ToString().Length - 2));

            _lessons = new List<Lesson>();
            var theorySplit = theory.ToString().Split(';');
            var t = new string[5];
            for (int i = 0, j = 0; i < theorySplit.Length; ++i, ++j)
            {
                t[j] = theorySplit[i];
                if (j != 4) continue;
                _lessons.Add(new Lesson
                {
                    TeacherName = t[0],
                    GroupName = t[1],
                    Room = t[2],
                    StartTime = t[3],
                    FinishTime = t[4],
                    IsReserved = "",
                    MeetPoint = "",
                    StudentName = ""
                });
                j = -1;
            }

            var practiceSplit = practice.ToString().Split(';');
            for (int i = 0, j = 0; i < practiceSplit.Length; ++i, ++j)
            {
                t[j] = practiceSplit[i];
                if (j != 4) continue;
                _lessons.Add(new Lesson
                {
                    TeacherName = t[0],
                    StudentName = t[1],
                    MeetPoint = t[2],
                    StartTime = t[3],
                    FinishTime = t[4],
                    GroupName = "",
                    IsReserved = "",
                    Room = ""
                });
                j = -1;
            }
        } 
    }
}
