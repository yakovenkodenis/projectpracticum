
namespace Autoschool
{
    public class User
    {
        public string Id { get; set; }
        public string Login { get; set; }
        public string Password { get; set; }
        public string Role { get; set; }
        public string AutoschoolId { get; set; }
        public string Email { get; set; }
        public string Name { get; set; }
        public string Telephone { get; set; }
        public string Address { get; set; }

        public override string ToString()
        {
            return "Id: " + Id + "\tLogin: " + Login + "\tPassword: " + Password + "\tRole: " + Role +
                   "\tAutoschoolId: " + AutoschoolId + "\tEmail: " + Email + "\tName: " + Name + "\tTelephone: " +
                   Telephone + "\tAddress: " + Address;
        }
    }
}
