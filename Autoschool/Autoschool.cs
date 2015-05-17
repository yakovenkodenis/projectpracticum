
namespace Autoschool
{
    class School
    {
        public string Id { get; set; }
        public string Name { get; set; }
        public string Contacts { get; set; }
        public string Info { get; set; }
        public string Price { get; set; }
        public string StudentCode { get; set; }
        public string TeacherCode { get; set; }

        public override string ToString()
        {
            return "Id: " + Id + "\tName: " + Name + "\tContacts: " + Contacts + "\tInfo: " + Info + "\tPrice: " + Price +
                   "\tStudentCode: " + StudentCode + "\tTeacherCode: " + TeacherCode;
        }
    }
}
